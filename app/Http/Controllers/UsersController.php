<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
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
            $users = User::where('name', 'like', "%$keyword%")->orWhere('email', 'like', "%$keyword%")->paginate($perPage);
        } else {
            $users = User::latest()->paginate($perPage);
        }

        return view('pages.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $parents = User::all();

        return view('pages.users.create', compact('parents'));
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
        $this->validate($request, [
			'name' => 'required',
			'email' => 'required|email|unique:users,email',
			'password' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif'
		]);

        $requestData = $request->all();

        $requestData['password'] = bcrypt($requestData['password']);

        $requestData['is_active'] = isset($requestData['is_active'])?1:0;

        if ($request->hasFile('image')) {
            $requestData['image'] = uploadFile($request, 'image', public_path('uploads/users'));
        }

        if(($count = User::all()->count()) && $count == 0) {

            $requestData['is_admin'] = 1;
        }

        User::create($requestData);

        return redirect('admin/users')->with('flash_message', 'User added!');
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
        $user = User::findOrFail($id);

        return view('pages.users.show', compact('user'));
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
        $user = User::findOrFail($id);

        return view('pages.users.edit', compact('user'));
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
        $this->validate($request, [
			'name' => 'required',
			'email' => 'required|email|unique:users,email,' . $id,
            'image' => 'image|mimes:jpeg,png,jpg,gif'
		]);

        $requestData = $request->all();

        if ($request->hasFile('image')) {
            $requestData['image'] = uploadFile($request, 'image', public_path('uploads/users'));
        }

        $user = User::findOrFail($id);

        if($user->is_admin == 0) {
            $requestData['is_active'] = isset($requestData['is_active']) ? 1 : 0;
        }

        $user->update($requestData);

        return redirect('admin/users')->with('flash_message', 'User updated!');
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
        User::destroy($id);

        return redirect('admin/users')->with('flash_message', 'User deleted!');
    }
}
