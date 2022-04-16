<?php

namespace App\Http\Controllers\Versiondos\Ventas\ReporteVentas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ventas;
use App\gastos;
use App\ingresosCajasVentas;

class MostrarReporteVentasController extends Controller
{
    public function MostrarReporteVentas(Request $request)
    {

        $idUsuario = $request->header('usuid');
        $re_fechainicio = $request['re_fechainicio'];
        $re_fechafinal  = $request['re_fechafinal'];


        if($idUsuario == 1 || $idUsuario == 2){
            $idSucursal = 1;
        }else{
            $idSucursal = 2;
        }

        $ventas = ventas::where('sucursal_id', $idSucursal)
                            ->whereBetween('created_at', [$re_fechainicio, $re_fechafinal])
                            ->where('estadoSunat', 0)
                            ->get();

        $rpta_numeroVentas   = sizeof($ventas);
        $rpta_totalVentas    = 0;
        $rpta_numeroTarjetas = 0;
        $rpta_totalTarjetas  = 0;
        $rpta_numeroEfectivo = 0;
        $rpta_totalEfectivo  = 0;

        $rpta_numeroCancelados = 0;
        $rpta_totalCancelados = 0;

        $rpta_numeroGastos = 0;
        $rpta_totalGastos = 0;

        $rpta_numeroIngresos = 0;
        $rpta_totalIngresos = 0;

        foreach($ventas as $venta){
            $rpta_totalVentas = doubleval($rpta_totalVentas) + doubleval($venta->total);

            if( $venta->tipoMoneda_id == 3){
                $rpta_numeroTarjetas = doubleval($rpta_numeroTarjetas) + 1;
                $rpta_totalTarjetas = doubleval($rpta_totalTarjetas) + doubleval($venta->total);
            }else if($venta->tipoMoneda_id == 1){
                $rpta_numeroEfectivo = doubleval($rpta_numeroEfectivo) + 1;
                $rpta_totalEfectivo = doubleval($rpta_totalEfectivo) + doubleval($venta->total);
            }

        }

        $ventasCanceladas = ventas::where('sucursal_id', $idSucursal)
                            ->whereBetween('created_at', [$re_fechainicio, $re_fechafinal])
                            ->where('estadoSunat', 2)
                            ->get();

        $rpta_numeroCancelados   = sizeof($ventasCanceladas);

        foreach($ventasCanceladas as $ventaCancelada){
            $rpta_totalCancelados = doubleval($rpta_totalCancelados) + doubleval($ventaCancelada->total);
        }

        $gastos = gastos::whereBetween('created_at', [$re_fechainicio, $re_fechafinal])
                        ->get();

        $rpta_numeroGastos = sizeof($gastos);
        foreach($gastos as $gasto){
            $rpta_totalGastos = doubleval($rpta_totalGastos) + doubleval($gasto->gasto);
        }

        $ingresos = ingresosCajasVentas::whereBetween('created_at', [$re_fechainicio, $re_fechafinal])
                                        ->get();

        $rpta_numeroIngresos = sizeof($ingresos);

        foreach($ingresos as $ingreso){
            $rpta_totalIngresos = doubleval($rpta_totalIngresos) + doubleval($ingreso->ingreso);
        }

            
        $rpta = array(
            'response'  => true,
            'rpta_numeroVentas'   => $rpta_numeroVentas,
            'rpta_totalVentas'    => $rpta_totalVentas,
            'rpta_numeroTarjetas' => $rpta_numeroTarjetas,
            'rpta_totalTarjetas'  => $rpta_totalTarjetas,
            'rpta_numeroEfectivo' => $rpta_numeroEfectivo,
            'rpta_totalEfectivo'  => $rpta_totalEfectivo,
            'rpta_numeroCancelados' => $rpta_numeroCancelados,
            'rpta_totalCancelados' => $rpta_totalCancelados,
            'rpta_numeroGastos'    => $rpta_numeroGastos,
            'rpta_totalGastos'     => $rpta_totalGastos,
            'rpta_numeroIngresos'  => $rpta_numeroIngresos,
            'rpta_totalIngresos'   => $rpta_totalIngresos
        );

        return json_encode($rpta);



    }
}
