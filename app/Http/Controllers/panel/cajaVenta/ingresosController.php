<?php

namespace App\Http\Controllers\panel\cajaVenta;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\ingresosCajasVentas;
use App\usuariosSucursales;

class ingresosController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        return view('react.app');
    }

    public function tb_ingresos(Request $request)
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

        
        $ingresos = ingresosCajasVentas::join('cajasVentas as cv','ingresosCajasVentas.cajaVenta_id', 'cv.id')
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
                                        ->orderby('ingresosCajasVentas.id', 'desc')
                                        ->paginate(10, array(
                                            'ingresosCajasVentas.id         as idIngreso',
                                            's.nombre                       as nombreSucursal',
                                            'cv.numero                      as numeroCajaVenta',
                                            'ingresosCajasVentas.created_at as fechaIngreso',
                                            'ingresosCajasVentas.numero     as numeroIngreso',
                                            'ingresosCajasVentas.ingreso    as ingreso',
                                            'ingresosCajasVentas.motivo     as motivoIngreso'
                                        ));

        $rpta = array(
            'respuesta' => true,
            'tb_ingresos' => $ingresos
        );
        return json_encode($rpta);
                             


    }
}
