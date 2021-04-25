<?php

namespace App\Http\Controllers\top;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ingresosCajasVentas;
use App\cajasVentas;

class ingresosCajaVentaController extends Controller
{
    public function registarIngresoCajaVenta(Request $request)
    {
        date_default_timezone_set("America/Lima");
        $idCajaVenta        = $request['idCajaVenta'];
        $ingresoRegistrado  = $request['ingreso'];
        $motivo             = $request['motivo'];

        $numeroIngreso = ingresosCajasVentas::where('cajaVenta_id', $idCajaVenta)
                                             ->count();

        $ingresoCajaVenta               = new ingresosCajasVentas;
        $ingresoCajaVenta->cajaVenta_id = $idCajaVenta;
        $ingresoCajaVenta->numero       = $numeroIngreso+1;
        $ingresoCajaVenta->ingreso      = $ingresoRegistrado;
        $ingresoCajaVenta->motivo       = $motivo;
        $ingresoCajaVenta->created_at   = date('Y-m-d H:i:s');
        $ingresoCajaVenta->save();

        if($ingresoCajaVenta->save()){
            $respuesta = true;
        }else{
            $respuesta = false;
        }

        $cajaVenta              = cajasVentas::find($idCajaVenta);
        $cajaVenta->totalCierre = $cajaVenta->totalCierre + $ingresoRegistrado;
        $cajaVenta->update();

        $rpta = array(
            'respuesta'     => $respuesta,
        );

        return json_encode($rpta);
    }
}
