<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function credentials(Request $request) {
        return $request->only($this->username(), 'password');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    public function login() {
        $credentials = request()->validate(
            [
                'email' => 'required|email',
                'pwd' => 'required'
            ]
        );

        $User = User::select('*')->where('email', $credentials['email'])->first();


        if ($User !== null && Hash::check($credentials['pwd'], $User->pwd)) {
            if (Auth::Login($User, true)) {
                return redirect()->intended('/');
            } else {
                return redirect()
                    ->to('/login')
                    ->withErrors([$this->username() => Lang::get('auth.failed')])
                    ->withInput(['email' => $credentials['email']]);
            }
        } else {
            return redirect()
                ->to('/login')
                ->withErrors([$this->username() => Lang::get('auth.failed')])
                ->withInput(['email' => $credentials['email']]);
        }

    }


    function logout() {
        Auth::logout();
        return redirect()->to('/login');
    }
}
