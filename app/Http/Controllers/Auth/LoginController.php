<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Auth;
use App\User;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {
        $username   = $request->username;
        $contrasena = $request->password;
        $this->validate($request, [
            'username'    => 'required',
            'password'    => 'required',
        ]);
        $usuario = User::where('username', $username)
                        ->first();
        if($usuario){
            if (Auth::attempt(['username' => $username, 'password' => $contrasena])) {
                $user = $this->guard()->user();
                return redirect()->intended($this->redirectPath());
                
            }else{
                return redirect()->back()
                ->withInput()
                ->withErrors([
                    'login' => 'These credentials do not match our records.',
                ]);
            }
        }else{
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'login' => 'These credentials do not match our records.',
                ]);
        }
    }

}
