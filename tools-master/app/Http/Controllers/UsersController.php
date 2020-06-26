<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Avatars;

class UsersController extends BaseController {
    function index() {
        $users = User::get();
        $Avatars = new Avatars();

        return view('users.index')
            ->with('users', $users)
            ->with('title', 'Users')
            ->with('avatarHelper', $Avatars);
    }

    function show($id) {
        return $this->edit($id);
    }


    function create() {
        $roles = config('adrenalads.roles');
        $Avatars = new Avatars();
        $avatars = $Avatars->get_avatars();

        $User = new User();
        return view('users.editor')
            ->with('avatars', $avatars)
            ->with('avatarHelper', $Avatars)
            ->with('user', $User)
            ->with('title', 'New User')
            ->with('roles', $roles);
    }

    function store(Request $request) {
        $input = request()->only('email','pwd','fname','lname','role','avatar');
        $validator = Validator::make($input,[
            'email' => 'email|required|unique:tools_logins',
            'pwd' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'role' => 'required',
            'avatar' => 'required',
        ]);

        if($validator->fails())
        {
            return redirect()->action('UsersController@create')->withInput()->withErrors($validator->errors());
        }

        $input['date_orig'] = Carbon::now();
        $input['date_update'] = Carbon::now();
        $input['pwd'] = bcrypt($input['pwd']);
        $user = User::create($input);

        session()->flash('success',$user->user_fname,' has been successfully added');
        return redirect()->to('users');
    }


    function edit($id) {
        $user = User::findOrFail($id);

        $roles = config('adrenalads.roles');
        $Avatars = new Avatars();
        $avatars = $Avatars->get_avatars();

        return view('users.editor')
            ->with('title', 'Edit User: ' . $user->full_name)
            ->with('edit', true)
            ->with('avatars', $avatars)
            ->with('avatarHelper', $Avatars)
            ->with('user', $user)
            ->with('roles', $roles);
    }

    function update(Request $request, $id) {
        $id = $request->get('hidden_user_id');
        $user = User::findOrFail($id);

        $user->date_orig = Carbon::now();
        $user->date_update = Carbon::now();

        foreach (array_keys($user->toArray()) as $attr) {
            if ($attr != 'user_id' && $request->get($attr)) {

                $user->$attr = $request->get($attr);
            }
        }

        $user->pwd = bcrypt($request->get('pwd'));

        $user->save();
        return redirect()->to('users');
    }

}
