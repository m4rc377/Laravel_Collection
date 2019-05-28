<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Document;
use App\Models\DocumentType;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentsController extends Controller
{
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
            $documents = Document::latest()->paginate($perPage);
        } else {
            $documents = Document::latest()->paginate($perPage);
        }

        return view('pages.documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $document_types = DocumentType::all();

        $users = User::all();

        return view('pages.documents.create', compact('document_types', 'users'));
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
        $this->do_validate($request, 0);
        
        $requestData = $request->except(['_token']);

        $requestData['file'] = uploadFile($request, 'file', public_path('uploads/documents'));

        $requestData['created_by_id'] = Auth::user()->id;

        Document::create($requestData);

        return redirect('admin/documents')->with('flash_message', 'Document added!');
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
        $document = Document::findOrFail($id);

        return view('pages.documents.show', compact('document'));
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
        $document = Document::findOrFail($id);

        $document_types = DocumentType::all();

        $users = User::all();

        return view('pages.documents.edit', compact('document', 'document_types', 'users'));
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
        
        $document = Document::findOrFail($id);
        $document->update($requestData);

        return redirect('admin/documents')->with('flash_message', 'Document updated!');
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
        Document::destroy($id);

        return redirect('admin/documents')->with('flash_message', 'Document deleted!');
    }

    protected function do_validate($request, $is_create = 1)
    {
        $mimes = 'mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,xls,xlsx,odt,dot,html,htm,rtf,ods,xlt,csv,bmp,odp,pptx,ppsx,ppt,potm';

        $this->validate($request, [
            'name' => 'required',
            'file' => ($is_create == 0? $mimes:"required|" . $mimes)
        ]);
    }
}
