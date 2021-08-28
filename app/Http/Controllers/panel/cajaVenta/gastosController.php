<?php

namespace App\Http\Controllers\panel\cajaVenta;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\usuariosSucursales;
use App\gastos;

class gastosController extends Controller
{
    
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        return view('react.app');
    }

    public function tb_gastos(Request $request)
    {
        // $idUsuario   = $request['idUsuario'];
        $idSucursal     = $request['idSucursal'];
        // $idUsuario      = $request->header('usuid');
        $idUsuario      = $request->header('usuid');

        if($idSucursal){
            if($idSucursal == 0){
                $sucursalesUsuario  = usuariosSucursales::where('user_id', $idUsuario)
                                                        ->get([
                                                            'sucursal_id'
                                                        ]);
            }
        }else{
            $sucursalusuarioPredeterminado  = usuariosSucursales::where('user_id', $idUsuario)
                                                                ->where('predeterminado', 1)
                                                                ->first([
                                                                    'sucursal_id'
                                                                ]);
            if(!$sucursalusuarioPredeterminado){
                $sucursalusuarioPredeterminado = 0;
                
            }else{
                $sucursalusuarioPredeterminado = $sucursalusuarioPredeterminado->sucursal_id;
            }
        }
        

        $sucursalesUsuario  = usuariosSucursales::where('user_id', $idUsuario)
                                                ->get(); 

        
        $gastos = gastos::join('cajasVentas as cv','gastos.cajaVenta_id', 'cv.id')
                        ->join('sucursales as s', 's.id', 'cv.sucursal_id')
                        ->where(function ($query) use( $sucursalesUsuario , $idSucursal, $sucursalusuarioPredeterminado ) {
                                    
                            if($idSucursal){
                                if($idSucursal == 0){
                                    if(sizeof($sucursalesUsuario) > 0 ){
                                        foreach($sucursalesUsuario as $sucursalUsuario){
                                            $query->where('s.id',  $sucursalUsuario->sucursal_id);
                                        }
                                    }else{
                                        $query->where('s.id',  0);
                                    } 
                                }else{
                                    $query->where('id.id',  $idSucursal);   
                                }

                            }else{
                                $query->where('s.id', $sucursalusuarioPredeterminado);
                            }
                            
                            
                        })
                        ->orderby('gastos.id', 'desc')
                        ->paginate(10, array(
                            'gastos.id          as idGasto',
                            's.nombre           as nombreSucursal',
                            'cv.numero          as numeroCajaVenta',
                            'gastos.created_at  as fechaGasto',
                            'gastos.numero      as numeroGasto',
                            'gastos.gasto       as gasto',
                            'gastos.motivo      as motivoGasto'
                        ));

        $rpta = array(
            'respuesta' => true,
            'tb_gastos' => $gastos
        );
        return json_encode($rpta);
                             


    }

}
