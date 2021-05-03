<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tiposComprobantes;

class tiposcomprobanteController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public static function index()
    {
        $tiposcomprobante = tiposComprobantes::all();
        return $tiposcomprobante;        
    }

    public static function factura()
    {
        $tiposcomprobante = tiposComprobantes::where('codigo', '01')->first();
        return $tiposcomprobante;        
    }

    public static function boleta()
    {
        $tiposcomprobante = tiposComprobantes::where('codigo', '03')->first();
        return $tiposcomprobante;        
    }

    public function facturaReact(){
        $tiposcomprobante = tiposComprobantes::where('codigo', '01')->first();
        return $tiposcomprobante; 
    }

    public function boletaReact(){
        $tiposcomprobante = tiposComprobantes::where('codigo', '03')->first();
        return $tiposcomprobante; 
    }

    public function ventaReact(Request $request){
        $idUsuario = $request->header('usuid');
        if($idUsuario == 1 || $idUsuario == 2){
            // $idSucursal                  = env('sucursalId');
            $idSucursal = 1;
        }else{
            $idSucursal = 2;
        }
        $tiposcomprobante = tiposComprobantes::where('codigo', '71')
                                            ->where('sucursal_id', $idSucursal)
                                            ->first();

        $rpta = array(
            'sucursalId'         => $idSucursal,
            'tiposcomprobante'   => $tiposcomprobante
        );
        
        return json_encode($rpta);
    }

}
