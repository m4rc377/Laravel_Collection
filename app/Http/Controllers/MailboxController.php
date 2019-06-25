<?php

namespace App\Http\Controllers;

use App\Helpers\MailerFactory;
use App\Models\MailboxFolder;
use App\Models\Mailbox;
use App\Models\MailboxAttachment;
use App\Models\MailboxFlags;
use App\Models\MailboxReceiver;
use App\Models\MailboxTmpReceiver;
use App\Models\MailboxUserFolder;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MailboxController extends Controller
{
    protected $mailer;

    protected $folders = array();

    public function __construct(MailerFactory $mailer)
    {
        $this->mailer = $mailer;

        $this->getFolders();
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

        $messages = $this->getData($keyword, $perPage, $folder);

        $unreadMessages = $this->getUnreadMessages();

        return view('pages.mailbox.index', compact('folders', 'messages', 'unreadMessages'));
    }


    public function create()
    {
        $folders = $this->folders;

        $unreadMessages = $this->getUnreadMessages();

        $users = User::where('is_active', 1)->where('id', '!=', Auth::user()->id)->get();

        return view('pages.mailbox.compose', compact('folders', 'unreadMessages', 'users'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required',
            'receiver_id' => 'required'
        ]);

        try {
            $this->validateAttachments($request);
        } catch (\Exception $ex) {
            return redirect('admin/mailbox-create')->with('flash_error', $ex->getMessage());
        }

        $receiver_ids = $request->receiver_id;

        $subject = $request->subject;

        $body = $request->body;

        $submit = $request->submit;


        // save message
        $mailbox = new Mailbox();

        $mailbox->subject = $subject;
        $mailbox->body = $body;
        $mailbox->sender_id = Auth::user()->id;
        $mailbox->time_sent = date("Y-m-d H:i:s");
        $mailbox->parent_id = 0;

        $mailbox->save();


        // save receivers and flags
        $this->save($submit, $receiver_ids, $mailbox);


        // save attachments if found
        $this->uploadAttachments($request, $mailbox);


        // check for the submit button and whether to send or save as draft
        if($request->submit == 1) {

            $this->mailer->sendMailboxEmail($mailbox);

            return redirect('admin/mailbox/Sent')->with('flash_message', 'Message sent');
        }

        return redirect('admin/mailbox/Drafts')->with('flash_message', 'Message saved as draft');
    }


    /**
     * show email
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $folders = $this->folders;

        $unreadMessages = $this->getUnreadMessages();

        $mailbox = Mailbox::find($id);

//        $folder = $mailbox->userFolder()->folder()->first();

        // if this message from "Inbox" then set is_unread=0
        if(($flag = $mailbox->flag()) && isset($flag->is_unread) && $flag->is_unread == 1) {
            $flag->is_unread = 0;
            $flag->save();
        }


        return view('pages.mailbox.show', compact('folders', 'unreadMessages', 'mailbox'));
    }


    /**
     * toggle important
     *
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleImportant(Request $request)
    {
        if(!$request->mailbox_flag_ids || count($request->mailbox_flag_ids) == 0)
            return response()->json(['state' => 0, 'msg' => 'required mailbox_flag_ids'], 500);

        $updated = [];

        foreach ($request->mailbox_flag_ids as $id) {

            $mailbox_flag = MailboxFlags::find($id);

            $mailbox_flag->is_important = ($mailbox_flag->is_important==0?1:0);

            $mailbox_flag->save();

            $updated[] = $mailbox_flag;
        }

        return response()->json(['state' => 1, 'msg' => 'updated sucessfully', 'updated' => $updated], 200);
    }


    /**
     * trash
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function trash(Request $request)
    {
        if(!$request->mailbox_user_folder_ids || count($request->mailbox_user_folder_ids) == 0)
            return response()->json(['state' => 0, 'msg' => 'required mailbox_user_folder_id'], 500);

        $updated = [];

        $trashFolder = MailboxFolder::where('title', 'Trash')->first();

        foreach ($request->mailbox_user_folder_ids as $id) {

            $mailbox_user_folder = MailboxUserFolder::find($id);

            $mailbox_user_folder->folder_id = $trashFolder->id;

            $mailbox_user_folder->save();

            $updated[] = $mailbox_user_folder;
        }

        return response()->json(['state' => 1, 'msg' => 'messages moved to trashed folder', 'updated' => $updated], 200);
    }


    /**
     * get Folders
     */
    private function getFolders(): void
    {
        $this->folders = MailboxFolder::all();
    }

    /**
     * getData
     *
     *
     * @param $keyword
     * @param $perPage
     * @param $foldername
     * @return array
     */
    private function getData($keyword, $perPage, $foldername)
    {
        $folder = MailboxFolder::where('title', $foldername)->first();

        if($foldername == "Inbox") {

            $query = Mailbox::join('mailbox_receiver', 'mailbox_receiver.mailbox_id', '=', 'mailbox.id')
                    ->join('mailbox_user_folder', 'mailbox_user_folder.user_id', '=', 'mailbox_receiver.receiver_id')
                    ->join('mailbox_flags', 'mailbox_flags.user_id', '=', 'mailbox_user_folder.user_id')
                    ->where('mailbox_receiver.receiver_id', Auth::user()->id)
                    ->where('mailbox_user_folder.folder_id', $folder->id)
                    ->where('sender_id', '!=', Auth::user()->id)
                    ->where('parent_id', 0)
                    ->whereRaw('mailbox.id=mailbox_receiver.mailbox_id')
                    ->whereRaw('mailbox.id=mailbox_flags.mailbox_id')
                    ->whereRaw('mailbox.id=mailbox_user_folder.mailbox_id')
                    ->select(["*", "mailbox.id as id", "mailbox_flags.id as mailbox_flag_id", "mailbox_user_folder.id as mailbox_folder_id"]);
        } else if ($foldername == "Sent" || $foldername == "Drafts") {
            $query = Mailbox::join('mailbox_user_folder', 'mailbox_user_folder.mailbox_id', '=', 'mailbox.id')
                ->join('mailbox_flags', 'mailbox_flags.user_id', '=', 'mailbox_user_folder.user_id')
                ->where('mailbox_user_folder.folder_id', $folder->id)
                ->where('mailbox_user_folder.user_id', Auth::user()->id)
                ->where('parent_id', 0)
                ->whereRaw('mailbox.id=mailbox_flags.mailbox_id')
                ->whereRaw('mailbox.id=mailbox_user_folder.mailbox_id')
                ->select(["*", "mailbox.id as id", "mailbox_flags.id as mailbox_flag_id", "mailbox_user_folder.id as mailbox_folder_id"]);
        } else {
            $query = Mailbox::join('mailbox_user_folder', 'mailbox_user_folder.mailbox_id', '=', 'mailbox.id')
                ->join('mailbox_flags', 'mailbox_flags.user_id', '=', 'mailbox_user_folder.user_id')
                ->leftJoin('mailbox_receiver', 'mailbox_receiver.mailbox_id', '=', 'mailbox.id')
                ->where(function ($query) {
                    $query->where('mailbox_user_folder.user_id', Auth::user()->id)
                          ->orWhere('mailbox_receiver.receiver_id', Auth::user()->id);
                })
                ->where('mailbox_user_folder.folder_id', $folder->id)
                ->where('parent_id', 0)
                ->whereRaw('mailbox.id=mailbox_flags.mailbox_id')
                ->whereRaw('mailbox.id=mailbox_user_folder.mailbox_id')
                ->whereRaw('mailbox_user_folder.user_id!=mailbox_receiver.receiver_id')
                ->select(["*", "mailbox.id as id", "mailbox_flags.id as mailbox_flag_id", "mailbox_user_folder.id as mailbox_folder_id"]);
        }


        if (!empty($keyword)) {
            $query->where('subject', 'like', "%$keyword%");
        }

        $query->orderBy('mailbox.id', 'DESC');

        $messages = $query->paginate($perPage);

        return $messages;
    }


    /**
     * get Unread Messages
     *
     *
     * @return mixed
     */
    private function getUnreadMessages()
    {
        $folder = MailboxFolder::where('title', "Inbox")->first();

        $messages = Mailbox::join('mailbox_flags', 'mailbox_flags.mailbox_id', '=', 'mailbox.id')
                    ->join('mailbox_receiver', 'mailbox_receiver.mailbox_id', '=', 'mailbox.id')
                    ->join('mailbox_user_folder', 'mailbox_user_folder.user_id', '=', 'mailbox_receiver.receiver_id')
                    ->where('mailbox_receiver.receiver_id', Auth::user()->id)
                    ->where('parent_id', 0)
                    ->where('mailbox_flags.is_unread', 1)
                    ->where('mailbox_user_folder.folder_id', $folder->id)
                    ->where('sender_id', '!=', Auth::user()->id)
                    ->whereRaw('mailbox.id=mailbox_receiver.mailbox_id')
                    ->whereRaw('mailbox.id=mailbox_flags.mailbox_id')
                    ->whereRaw('mailbox.id=mailbox_user_folder.mailbox_id')
                    ->count();

        return $messages;
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
     * save
     *
     *
     * @param $submit
     * @param $receiver_ids
     * @param $mailbox
     */
    private function save($submit, $receiver_ids, $mailbox)
    {

        // We will save two records in tables mailbox_user_folder and mailbox_flags
        // for both the sender and the receivers
        // For the sender perspective the message will be in the "Sent" folder
        // For the receiver perspective the message will be in the "Inbox" folder


        // 1. The sender
        // save folder as "Sent" or "Drafts" depending on button
        $mailbox_user_folder = new MailboxUserFolder();

        $mailbox_user_folder->mailbox_id = $mailbox->id;

        $mailbox_user_folder->user_id = $mailbox->sender_id;

        // if click "Draft" button save into "Drafts" folder
        if($submit == 2) {
            $mailbox_user_folder->folder_id = MailboxFolder::where("title", "Drafts")->first()->id;
        } else {
            $mailbox_user_folder->folder_id = MailboxFolder::where("title", "Sent")->first()->id;
        }

        $mailbox_user_folder->save();

        // save flags "is_unread=0"
        $mailbox_flag = new MailboxFlags();

        $mailbox_flag->mailbox_id = $mailbox->id;

        $mailbox_flag->user_id = $mailbox->sender_id;;

        $mailbox_flag->is_unread = 0;

        $mailbox_flag->is_important = 0;

        $mailbox_flag->save();


        // 2. The receivers
        // if there are receivers and sent button clicked then save into flags, folders and receivers
        if($submit == 1) {

            foreach ($receiver_ids as $receiver_id) {

                // save receiver
                $mailbox_receiver = new MailboxReceiver();

                $mailbox_receiver->mailbox_id = $mailbox->id;

                $mailbox_receiver->receiver_id = $receiver_id;

                $mailbox_receiver->save();


                // save folder as "Inbox"
                $mailbox_user_folder = new MailboxUserFolder();

                $mailbox_user_folder->mailbox_id = $mailbox->id;

                $mailbox_user_folder->user_id = $receiver_id;

                $mailbox_user_folder->folder_id = MailboxFolder::where("title", "Inbox")->first()->id;

                $mailbox_user_folder->save();


                // save flags "is_unread=1"
                $mailbox_flag = new MailboxFlags();

                $mailbox_flag->mailbox_id = $mailbox->id;

                $mailbox_flag->user_id = $receiver_id;

                $mailbox_flag->is_unread = 1;

                $mailbox_flag->save();
            }
        } else {

            // save into tmp receivers
            foreach ($receiver_ids as $receiver_id) {

                // save receiver
                $mailbox_receiver = new MailboxTmpReceiver();

                $mailbox_receiver->mailbox_id = $mailbox->id;

                $mailbox_receiver->receiver_id = $receiver_id;

                $mailbox_receiver->save();
            }
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

                $new_name = $filename . '-' . time().'.'.$extension;

                $file->move($destination, $new_name);

                $attachment = new MailboxAttachment();
                $attachment->mailbox_id = $mailbox->id;
                $attachment->attachment = $new_name;
                $attachment->save();
            }
        }
    }
}
