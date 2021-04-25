<?php

namespace App\Http\Controllers\Versiondos\Login;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $username   = $request->usuario;
        $contrasena = $request->contrasenia;

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
