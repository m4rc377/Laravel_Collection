<?php

namespace App\Http\Controllers;

use App\Helpers\MailerFactory;
use App\Models\ContactStatus;
use App\Models\Document;
use App\Models\Task;
use App\Models\TaskDocument;
use App\Models\TaskStatus;
use App\Models\TaskType;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{

    protected $mailer;

    public function __construct(MailerFactory $mailer)
    {
        $this->mailer = $mailer;
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
            $tasks = Task::latest()->paginate($perPage);
        } else {
            $tasks = Task::latest()->paginate($perPage);
        }

        return view('pages.tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $users = User::where('is_active', 1)->get();

        $documents = Document::where('status', 1)->get();

        $statuses = TaskStatus::all();

        $task_types = TaskType::all();

        $contact_statuses = ContactStatus::all();

        return view('pages.tasks.create', compact('users', 'documents', 'statuses', 'task_types', 'contact_statuses'));
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

        if(isset($requestData['documents'])) {

            $documents = $requestData['documents'];

            unset($requestData['documents']);

            $documents = array_filter($documents, function ($value) {
                return !empty($value);
            });
        }

        $requestData['created_by_id'] = Auth::user()->id;

        if(empty($requestData['contact_type'])) {

            $requestData['contact_id'] = null;
        }
        
        $task = Task::create($requestData);


        // insert documents
        if($task && $task->id) {

            if(isset($documents)) {

                $this->insertDocuments($documents, $task->id);
            }
        }


        // send notifications email
        if(getSetting("enable_email_notification") == 1 && isset($requestData['assigned_user_id'])) {

            $this->mailer->sendAssignTaskEmail("Task assigned to you", User::find($requestData['assigned_user_id']), $task);
        }

        return redirect('admin/tasks')->with('flash_message', 'Task added!');
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
        $task = Task::findOrFail($id);

        return view('pages.tasks.show', compact('task'));
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
        $task = Task::findOrFail($id);

        $users = User::where('is_active', 1)->get();

        $documents = Document::where('status', 1)->get();

        $statuses = TaskStatus::all();

        $task_types = TaskType::all();

        $selected_documents = $task->documents()->pluck('document_id')->toArray();

        $contact_statuses = ContactStatus::all();

        return view('pages.tasks.edit', compact('task', 'users', 'documents', 'statuses', 'task_types', 'selected_documents', 'contact_statuses'));
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

        if(isset($requestData['documents'])) {

            $documents = $requestData['documents'];

            unset($requestData['documents']);

            $documents = array_filter($documents, function ($value) {
                return !empty($value);
            });
        }

        if(empty($requestData['contact_type'])) {

            $requestData['contact_id'] = null;
        }

        $requestData['modified_by_id'] = Auth::user()->id;
        
        $task = Task::findOrFail($id);

        $old_assign_user_id = $task->assigned_user_id;

        $task->update($requestData);


        // delete documents if exist
        TaskDocument::where('task_id', $id)->delete();

        // insert documents
        if(isset($documents)) {

            $this->insertDocuments($documents, $id);
        }


        // send notifications email
        if(getSetting("enable_email_notification") == 1 && isset($requestData['assigned_user_id']) && $old_assign_user_id != $requestData['assigned_user_id']) {

                $this->mailer->sendAssignTaskEmail("Task assigned to you", User::find($requestData['assigned_user_id']), $task);
        }

        return redirect('admin/tasks')->with('flash_message', 'Task updated!');
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
        Task::destroy($id);

        return redirect('admin/tasks')->with('flash_message', 'Task deleted!');
    }


    /**
     * insert documents
     *
     *
     * @param $documents
     * @param $task_id
     */
    protected function insertDocuments($documents, $task_id)
    {
        foreach ($documents as $document) {

            $taskDocument = new TaskDocument();

            $taskDocument->document_id = $document;

            $taskDocument->task_id = $task_id;

            $taskDocument->save();
        }
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
            'name' => 'required'
        ]);
    }
}
