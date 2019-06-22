<?php

namespace App\Http\Controllers;

use App\Helpers\MailerFactory;
use App\Models\Mailbox;
use App\Models\MailboxAttachment;
use App\Models\MailboxReceiver;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MailboxController extends Controller
{
    protected $mailer;

    protected $folders = array(
        array("name"=>"Inbox", "icon" => "fa fa-inbox"),
        array("name"=>"Sent", "icon" => "fa fa-envelope-o"),
        array("name"=>"Drafts", "icon" => "fa fa-file-text-o"),
        array("name"=>"Trash", "icon" => "fa fa-trash-o"),
    );

    public function __construct(MailerFactory $mailer)
    {
        $this->mailer = $mailer;
    }


    /**
     * index
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $folder = "")
    {
        $keyword = $request->get('search');
        $perPage = 15;

        $folders = $this->folders;

        if(empty($folder)) {
            $folder = "Inbox";
        }

        $data = $this->getData($keyword, $perPage, $folder);

        list($messages, $unreadMessages) = $data;

        return view('pages.mailbox.index', compact('folders', 'messages', 'unreadMessages'));
    }


    public function create()
    {
        $folders = $this->folders;

        list($messages, $unreadMessages) = $this->getData("", 0, null);

        $users = User::where('is_active', 1)->where('id', '!=', Auth::user()->id)->get();

        return view('pages.mailbox.compose', compact('folders', 'unreadMessages', 'users'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required'
        ]);

        try {
            $this->validateAttachments($request);
        } catch (\Exception $ex) {
            return redirect('admin/mailbox-create')->with('flash_error', $ex->getMessage());
        }

        $receiver_ids = $request->receiver_id;

        $subject = $request->subject;

        $body = $request->body;


        // save message
        $mailbox = new Mailbox();

        $mailbox->subject = $subject;
        $mailbox->body = $body;
        $mailbox->sender_id = Auth::user()->id;
        $mailbox->time_sent = date("Y-m-d H:i:s");
        $mailbox->parent_id = 0;

        if($request->submit == 2 || !$receiver_ids) {
            $mailbox->folder = "Drafts";
            $mailbox->is_unread = 0;
        } else {
            $mailbox->is_unread = 1;
            $mailbox->folder = "Sent";
        }

        $mailbox->save();


        // save receivers if found
        if($receiver_ids) {

            foreach ($receiver_ids as $receiver_id) {
                $mailbox_receiver = new MailboxReceiver();

                $mailbox_receiver->mailbox_id = $mailbox->id;
                $mailbox_receiver->receiver_id = $receiver_id;
                $mailbox_receiver->save();
            }
        }


        // save attachments if found
        $this->uploadAttachments($request, $mailbox);


        // check for the submit button and whether to send or save as draft
        if($request->submit == 1) {
            if(!$receiver_ids) {
                return redirect('admin/mailbox/Drafts')->with('flash_warning', 'There is no receiver users! message will not be sent it will be saved as draft');
            }

            $this->mailer->sendMailboxEmail($mailbox);

            return redirect('admin/mailbox/Sent')->with('flash_message', 'Message sent');
        }

        return redirect('admin/mailbox/Drafts')->with('flash_message', 'Message saved as draft');
    }

    public function show()
    {
        $folders = $this->folders;

        return view('pages.mailbox.show', compact('folders'));
    }


    /**
     * getData
     *
     *
     * @param $keyword
     * @param $perPage
     * @param $folder
     * @return array
     */
    private function getData($keyword, $perPage, $folder = null)
    {
        $messages = [];

        if($folder != null) {
            if ($folder == "Inbox") {
                $query = Mailbox::join('mailbox_receivers', 'mailbox_receivers.mailbox_id', '=', 'mailboxes.id')
                    ->where('mailbox_receivers.receiver_id', Auth::user()->id)
                    ->where('folder', "Sent")
                    ->where('sender_id', '!=', Auth::user()->id)
                    ->where('parent_id', 0);
            } else if ($folder == "Sent" || $folder == "Drafts") {
                $query = Mailbox::where('sender_id', Auth::user()->id)
                    ->where('folder', $folder)
                    ->where('parent_id', 0);
            } else if ($folder == "Trash") {
                $query = Mailbox::join('mailbox_receivers', 'mailbox_receivers.mailbox_id', '=', 'mailboxes.id')
                    ->where(function ($query) {
                        $query->where('sender_id', Auth::user()->id)
                            ->orWhere('mailbox_receivers.receiver_id', Auth::user()->id);
                    })
                    ->where('folder', $folder)
                    ->where('parent_id', 0);
            }

            if (!empty($keyword)) {
                $query->where('subject', 'like', "%$keyword%");
            }

            $messages = $query->paginate($perPage);
        }



        $unreadMessages = Mailbox::join('mailbox_receivers', 'mailbox_receivers.mailbox_id', '=', 'mailboxes.id')
            ->where('mailbox_receivers.receiver_id', Auth::user()->id)
            ->where('sender_id', '!=', Auth::user()->id)
            ->where('folder', "Sent")
            ->where('parent_id', 0)
            ->where('is_unread', 1)->count();

        return [$messages, $unreadMessages];
    }


    /**
     * validateAttachments
     *
     *
     * @param $request
     * @throws \Exception
     */
    private function validateAttachments($request)
    {
        $check = [];

        if($request->hasFile('attachments')) {

            $allowedfileExtension = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'odt', 'dot', 'html', 'htm', 'rtf', 'ods', 'xlt', 'csv', 'bmp', 'odp', 'pptx', 'ppsx', 'ppt', 'potm'];

            $files = $request->file('attachments');

            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();

                if(!in_array($extension, $allowedfileExtension)) {
                    $check[] = $extension;
                }
            }
        }

        if(count($check) > 0) {
            throw new \Exception("One or more files contain invalid extensions: ". implode(",", $check));
        }
    }


    /**
     * uploadAttachments
     *
     *
     * @param $request
     * @param $mailbox
     */
    private function uploadAttachments($request, $mailbox)
    {
        $destination = public_path('uploads/mailbox/');

        if($request->hasFile('attachments')) {
            $files = $request->file('attachments');

            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();

                $new_name = time().'.'.$extension;

                $file->move($destination, $new_name);

                $attachment = new MailboxAttachment();
                $attachment->mailbox_id = $mailbox->id;
                $attachment->attachment = $new_name;
                $attachment->save();
            }
        }
    }
}
