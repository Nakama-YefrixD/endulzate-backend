<?php

namespace App\Http\Controllers\top;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\gastos;
use App\cajasVentas;

class gastosController extends Controller
{
    
    public function registrarGasto(Request $request)
    {
        date_default_timezone_set("America/Lima");
        $idCajaVenta        = $request['idCajaVenta'];
        $gastoRegistrado    = $request['gasto'];
        $motivo             = $request['motivo'];
        
        $numeroGasto = gastos::where('cajaVenta_id', $idCajaVenta)
                                ->count();

        $gasto                  = new gastos;
        $gasto->cajaVenta_id    = $idCajaVenta;
        $gasto->numero          = $numeroGasto+1;
        $gasto->gasto           = $gastoRegistrado;
        $gasto->motivo          = $motivo;
        $gasto->created_at      = date('Y-m-d H:i:s');
        if($gasto->save()){
            $respuesta = true;
        }else{
            $respuesta = false;
        }

        $cajaVenta              = cajasVentas::find($idCajaVenta);
        $cajaVenta->totalCierre = $cajaVenta->totalCierre - $gastoRegistrado;
        $cajaVenta->update();

        $rpta = array(
            'respuesta'     => $respuesta,
            'idCaja'        => $idCajaVenta,
            'motivo'        => $motivo,
            'gasto'         => $gasto

        );
        return json_encode($rpta);
        
    }

}
