<?php

namespace App\Http\Controllers\consultar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\usuariosSucursales;
use App\sucursales;


class consultarSucursales extends Controller
{

    public function consultarSucursales(Request $request)
    {
        $sucursales = sucursales::all();

        if(sizeof($sucursales) > 0){
            $respuesta = true;
        }else{
            $respuesta = false;
        }
        
        $rpta = array(
            'respuesta'   => $respuesta,
            'sucursales'  => $sucursales
        );

        return json_encode($rpta);

    }

    public function consultarSucursalesUsuario(Request $request)
    {
        // $idUsuario = $request['idUsuario'];
        // $idUsuario  = auth()->id();
        $idUsuario  = $request->header('usuid');

        $sucursalesUsuario  = usuariosSucursales::join('sucursales as s', 's.id', 'usuariosSucursales.sucursal_id')
                                                    ->where('usuariosSucursales.user_id', $idUsuario)
                                                    ->get([
                                                        's.id',
                                                        's.nombre'
                                                    ]);
        if(sizeof($sucursalesUsuario) > 0){
            $respuesta = true;
        }else{
            $respuesta = false;
        }
        
        $rpta = array(
            'respuesta'   => $respuesta,
            'sucursales'  => $sucursalesUsuario
        );

        return json_encode($rpta);
    }
}
