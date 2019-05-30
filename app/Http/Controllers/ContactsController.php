<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactDocument;
use App\Models\ContactEmail;
use App\Models\ContactPhone;
use App\Models\ContactStatus;
use App\Models\Document;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactsController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin:index-list_contacts|create-create_contact|show-view_contact|edit-edit_contact|destroy-delete_contact', ['except' => ['store', 'update']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $contacts = Contact::where('first_name', 'like', "%$keyword%")->orWhere('middle_name', 'like', "%$keyword%")->orWhere('last_name', 'like', "%$keyword%")->paginate($perPage);
        } else {
            $contacts = Contact::latest()->paginate($perPage);
        }

        return view('pages.contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $statuses = ContactStatus::all();

        $users = User::all();

        $documents = Document::all();

        return view('pages.contacts.create', compact('statuses', 'users', 'documents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->do_validate($request);

        $requestData = $request->all();

        $emails = $requestData['emails'];

        $phones = $request['phones'];

        unset($requestData['emails'], $requestData['phones']);

        if(isset($requestData['documents'])) {

            $documents = $requestData['documents'];

            unset($requestData['documents']);

            $documents = array_filter($documents, function ($value) {
                return !empty($value);
            });
        }

        $requestData['created_by_id'] = Auth::user()->id;

        $contact = Contact::create($requestData);

        $emails = array_filter($emails, function ($value) {
           return !empty($value);
        });

        $phones = array_filter($phones, function ($value) {
            return !empty($value);
        });

        // insert emails & phones
        if($contact && $contact->id) {

            $this->insertEmails($emails, $contact->id);

            $this->insertPhones($phones, $contact->id);

            if(isset($documents)) {

                $this->insertDocuments($documents, $contact->id);
            }
        }

        return redirect('admin/contacts')->with('flash_message', 'Contact added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        return view('pages.contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $contact = Contact::findOrFail($id);

        $statuses = ContactStatus::all();

        $users = User::all();

        $documents = Document::all();

        $selected_documents = $contact->documents()->pluck('document_id')->toArray();

        return view('pages.contacts.edit', compact('contact', 'statuses', 'users', 'documents', 'selected_documents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->do_validate($request);

        $requestData = $request->all();

        $emails = $requestData['emails'];

        $phones = $request['phones'];

        unset($requestData['emails'], $requestData['phones']);

        $emails = array_filter($emails, function ($value) {
            return !empty($value);
        });

        $phones = array_filter($phones, function ($value) {
            return !empty($value);
        });

        if(isset($requestData['documents'])) {

            $documents = $requestData['documents'];

            unset($requestData['documents']);

            $documents = array_filter($documents, function ($value) {
                return !empty($value);
            });
        }

        $requestData['modified_by_id'] = Auth::user()->id;

        $contact = Contact::findOrFail($id);

        $contact->update($requestData);

        // delete emails if exist
        ContactEmail::where('contact_id', $id)->delete();

        if($emails) {

            // insert
            $this->insertEmails($emails, $id);
        }

        // delete phones if exist
        ContactPhone::where('contact_id', $id)->delete();

        if($phones) {

            // insert
            $this->insertPhones($phones, $id);
        }

        // delete documents if exist
        ContactDocument::where('contact_id', $id)->delete();

        if(isset($documents)) {

            // insert
            $this->insertDocuments($documents, $id);
        }

        return redirect('admin/contacts')->with('flash_message', 'Contact updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Contact::destroy($id);

        return redirect('admin/contacts')->with('flash_message', 'Contact deleted!');
    }


    /**
     * do_validate
     *
     *
     * @param $request
     */
    protected function do_validate($request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required'
        ]);
    }


    /**
     * insert emails
     *
     *
     * @param $emails
     * @param $contact_id
     */
    protected function insertEmails($emails, $contact_id)
    {
        foreach ($emails as $email) {

            $contactEmail = new ContactEmail();

            $contactEmail->email = $email;

            $contactEmail->contact_id = $contact_id;

            $contactEmail->save();
        }
    }


    /**
     * insert phones
     *
     *
     * @param $phones
     * @param $contact_id
     */
    protected function insertPhones($phones, $contact_id)
    {
        foreach ($phones as $phone) {

            $contactPhone = new ContactPhone();

            $contactPhone->phone = $phone;

            $contactPhone->contact_id = $contact_id;

            $contactPhone->save();
        }
    }


    /**
     * insert documents
     *
     *
     * @param $documents
     * @param $contact_id
     */
    protected function insertDocuments($documents, $contact_id)
    {
        foreach ($documents as $document) {

            $contactDocument = new ContactDocument();

            $contactDocument->document_id = $document;

            $contactDocument->contact_id = $contact_id;

            $contactDocument->save();
        }
    }
}
