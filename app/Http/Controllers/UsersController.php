<?php

namespace App\Http\Controllers;

use App\Helpers\AppMailer;
use App\Http\Requests;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    protected $mailer;

    public function __construct(AppMailer $mailer)
    {
        $this->middleware('admin');

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

        $requestData = $request->except(['is_profile', '_token']);

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

        $is_profile = $request->is_profile;

        $requestData = $request->except(['is_profile', '_token']);

        if ($request->hasFile('image')) {
            $requestData['image'] = uploadFile($request, 'image', public_path('uploads/users'));
        }

        $user = User::findOrFail($id);

        if($user->is_admin == 0) {
            $requestData['is_active'] = isset($requestData['is_active']) ? 1 : 0;
        }

        $user->update($requestData);


        // send notification email
        if(!$is_profile && $user->is_admin == 0) {

            if($user->is_active == 1) {
                $subject = "Your mini crm account have been activated";
            } else {
                $subject = "Your mini crm account have been deactivated";
            }

            $this->mailer->sendActivateBannedEmail($subject, $user);
        }

        if(!$is_profile) {
            return redirect('admin/users')->with('flash_message', 'User updated!');
        } else {
            return redirect('admin/my-profile')->with('flash_message', 'User updated!');
        }
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


    /**
     * show user profile
     *
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProfile()
    {
        $user = User::findOrFail(Auth::user()->id);

        $is_profile = true;

        return view('pages.users.show', compact('user', 'is_profile'));
    }

    public function getEditProfile()
    {
        $user = User::findOrFail(Auth::user()->id);

        $is_profile = true;

        return view('pages.users.edit', compact('user', 'is_profile'));
    }


    public function getRole($id)
    {
        $user = User::findOrFail($id);

        $roles = Role::all();

        return view('pages.users.role', compact('user', 'roles'));
    }


    public function updateRole(Request $request, $id)
    {
        $this->validate($request, [
            'role_id' => 'required'
        ]);

        $user = User::findOrFail($id);

        $user->assignRole($request->role_id);

        return redirect('admin/users')->with('flash_message', 'Role updated!');
    }
}
