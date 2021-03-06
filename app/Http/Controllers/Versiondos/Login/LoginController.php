<?php

namespace App\Http\Controllers\Versiondos\Login;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $respuesta = true;
        $mensaje = "Bienvenido, ";
        $datos = [];
        $token ="";

        $username   = $request->usuario;
        $contrasena = $request->contrasenia;

        $this->validate($request, [
            'usuario'    => 'required',
            'contrasenia'    => 'required',
        ]);
        
        $usu = User::where('username', $username)
                    ->first([
                        'id as usuid',
                        'username as usuusuario',
                        'name as pernombre',
                        'email',
                        'imagen',
                        'remember_token as usutoken',
                        'password'
                    ]);

        if($usu){
            if (Hash::check($contrasena, $usu->password)) {

                $mensaje = "Bienvenido, ".$usu->username." es un gusto volver a verte por aquí";
                $datos = $usu;

            }else{
                $respuesta = false;
                $mensaje = "Lo sentimos, el usuario o contraseña es incorrecta";
            }
        }else{
            $respuesta = false;
            $mensaje = "Lo sentimos, el usuario o contraseña es incorrecta";
        }

        return response()->json([
            'respuesta' => $respuesta,
            'mensaje'   => $mensaje,
            'datos'     => $datos,
        ]);
    }
}
