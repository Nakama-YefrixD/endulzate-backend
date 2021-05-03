<?php

namespace App\Http\Controllers\top;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\cajasVentas;
use App\User;
use App\tiposMonedas;
use App\ventas;
use App\ingresosCajasVentas;
use App\gastos;
use DB;

class cajaVentaController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    // Api devuelve informacion de la caja venta actual 
    public function cajaVentaActual() 
    {
        $idUsuario  = auth()->id();

        $cajaVenta    = cajasVentas::join('sucursales as s', 's.id', 'cajasVentas.sucursal_id')
                                    ->where('user_id', $idUsuario)
                                    ->where('cierre', null)
                                    ->first([
                                        'cajasVentas.id                 as idCajaVenta',
                                        's.nombre                       as nombreSucursal',
                                        'cajasVentas.totalAperturo      as totalAperturo',
                                        'cajasVentas.totalCierre        as totalCierre'
                                    ]);
        if($cajaVenta){
            $respuesta = true;
        }else{
            $respuesta = false;
        }
        $user = User::find($idUsuario);
        $ultimoCierreCaja = cajasVentas::join('sucursales as s', 's.id', 'cajasVentas.sucursal_id')
                                    ->where('user_id', $idUsuario)
                                    ->where('estado', 1)
                                    ->first([
                                        'cajasVentas.totalCerro as totalCerroCajasVentas',
                                        's.nombre               as nombreSucursal',
                                        
                                    ]);
        if($ultimoCierreCaja){
            $totalApertura  = $ultimoCierreCaja->totalCerroCajasVentas;
            $sucursalNombre = $ultimoCierreCaja->nombreSucursal;
        }else{
            $totalApertura = 0;
            $sucursalNombre = $cajaVenta->nombreSucursal;
        }

        $rpta = array(
            'respuesta'      => $respuesta,
            'cajaVenta'      => $cajaVenta,
            'nombreUsuario'  => $user->username,
            'sucursalNombre' => $sucursalNombre,
            'totalApertura'  => $totalApertura

        );
        return json_encode($rpta);
    }

    public function totalCierreActual(Request $request)
    {
        $idCajaVenta = $request['idCajaVenta'];
        $totalCierre = cajasVentas::find($idCajaVenta);

        $rpta = array(
            'respuesta'     => true,
            'totalCierre'   => $totalCierre->totalCierre,
        );

        return json_encode($rpta);


    }

    public function aperturarCaja(Request $request)
    {
        date_default_timezone_set("America/Lima");
        
        // $idUsuario              = auth()->id();
        $idUsuario   = $request->header('usuid');
        $totalApertura          = $request['totalApertura'];
        $totalAperturo          = $request['totalAperturo'];
        $observacionesApertura  = $request['observacionesApertura'];

        if($idUsuario == 1 || $idUsuario == 2){
            // $idSucursal                  = env('sucursalId');
            $idSucursal = 1;
        }else{
            $idSucursal = 2;
        }

        $numeroCajasVentas  = cajasVentas::where('sucursal_id', $idSucursal)
                                            ->count();

        if($numeroCajasVentas == 0){
            $estado = 1;
        }else{
            $estado = 0;
        }
        DB::beginTransaction();
        try {
            $cajaVenta = new cajasVentas;
            $cajaVenta->sucursal_id             = $idSucursal;
            $cajaVenta->user_id                 = $idUsuario;
            $cajaVenta->numero                  = $numeroCajasVentas+1;
            $cajaVenta->apertura                = date('Y-m-d H:i:s');
            $cajaVenta->totalApertura           = $totalApertura;
            $cajaVenta->totalAperturo           = $totalAperturo;
            $cajaVenta->totalCierre             = $totalAperturo;
            $cajaVenta->estado                  = $estado;
            $cajaVenta->observacionesApertura   = $observacionesApertura;
            
            if($cajaVenta->save()){
                $respuesta = true;
            }else{
                $respuesta = false;
            }

            DB::commit();

            $rpta = array(
                'respuesta' => $respuesta,
                'idCaja'    => $cajaVenta->id
            );
            
            return json_encode($rpta);

        }catch (\Exception $e) {
            DB::rollBack();
            //echo $datosProducto;
            echo json_encode($e->getMessage());

        }

    }

    public function cierreCajaVenta(Request $request)
    {
        date_default_timezone_set("America/Lima");
        $idCajaVenta        = $request['idCajaVenta'];
        $totalCerro         = $request['totalCerro'];
        $observacionCierre  = $request['observacionCierre'];
        DB::beginTransaction();
        try{
            $tiposMonedas       = tiposMonedas::where('codigo', 71)->first();
        
            $totalVentasTarjeta = ventas::where('tipoMoneda_id', $tiposMonedas->id)
                                        ->where('cajaVenta_id', $idCajaVenta)
                                        ->sum('total');
            $totalVentasEfectivo    = ventas::where('tipoMoneda_id', '!=',$tiposMonedas->id)
                                            ->where('cajaVenta_id', $idCajaVenta)
                                            ->sum('total');
            $totalVentas            = ventas::where('cajaVenta_id', $idCajaVenta)
                                            ->sum('total');
            $totalVentasCanceladas  = ventas::where('cajaVenta_id', $idCajaVenta)
                                            ->where('estadoSunat', 2)
                                            ->sum('total');
            $totalGastos            = gastos::where('cajaVenta_id', $idCajaVenta)
                                            ->sum('gasto');
            $totalIngresos          = ingresosCajasVentas::where('cajaVenta_id', $idCajaVenta)
                                                            ->sum('ingreso');
            $numeroVentasTarjeta    = ventas::where('tipoMoneda_id', $tiposMonedas->id)
                                            ->where('cajaVenta_id', $idCajaVenta)
                                            ->count();
            $numeroVentasEfectivo    = ventas::where('tipoMoneda_id', '!=',$tiposMonedas->id)
                                            ->where('cajaVenta_id', $idCajaVenta)
                                            ->count();
            $numeroVentas            = ventas::where('cajaVenta_id', $idCajaVenta)
                                            ->count();
            $numeroVentasCanceladas  = ventas::where('cajaVenta_id', $idCajaVenta)
                                            ->where('estadoSunat', 2)
                                            ->count();
            $numeroGastos            = gastos::where('cajaVenta_id', $idCajaVenta)
                                            ->count();
            $numeroIngresos          = ingresosCajasVentas::where('cajaVenta_id', $idCajaVenta)
                                                            ->count();
            $numeroItems             = ventas::join('detallesVentas as dv', 'dv.venta_id', 'ventas.id')
                                                ->where('ventas.cajaVenta_id', $idCajaVenta)
                                                ->sum('dv.cantidad');
            $numeroItemsCancelados   = ventas::join('detallesVentas as dv', 'dv.venta_id', 'ventas.id')
                                                ->where('ventas.estadoSunat', 2)
                                                ->where('ventas.cajaVenta_id', $idCajaVenta)
                                                ->sum('dv.cantidad');
            $cajaVenta              = cajasVentas::find($idCajaVenta);
            $ultimaCajaVenta        = cajasVentas::where('sucursal_id', $cajaVenta->sucursal_id)
                                                    ->where('estado', 1)
                                                    ->first();
            if($ultimaCajaVenta){
                $ultimaCajaVenta->estado = 0;
                $ultimaCajaVenta->update();
            }else{
                
            }

            $cajaVenta->cierre                  = date('Y-m-d H:i:s');
            // $cajaVenta->totalCierre             = ;
            $cajaVenta->totalCerro              = $totalCerro;
            $cajaVenta->totalVentasTarjeta      = $totalVentasTarjeta;
            $cajaVenta->totalVentasEfectivo     = $totalVentasEfectivo;
            $cajaVenta->totalVentas             = $totalVentas;
            $cajaVenta->totalVentasCanceladas   = $totalVentasCanceladas;
            $cajaVenta->totalGastos             = $totalGastos;
            $cajaVenta->totalIngresos           = $totalIngresos;
            $cajaVenta->numeroIngresos          = $numeroIngresos;
            $cajaVenta->numeroVentasTarjeta     = $numeroVentasTarjeta;
            $cajaVenta->numeroVentasEfectivo    = $numeroVentasEfectivo;
            $cajaVenta->numeroVentas            = $numeroVentas;
            $cajaVenta->numeroGastos            = $numeroGastos;
            $cajaVenta->numeroItems             = $numeroItems;
            $cajaVenta->numeroVentasCanceladas  = $numeroVentasCanceladas;
            $cajaVenta->numeroItemsCancelados   = $numeroItemsCancelados;
            $cajaVenta->observacionesCierre     = $observacionCierre;
            $cajaVenta->estado                  = 1;

            if($cajaVenta->update()){
                $respuesta = true;
            }else{
                $respuesta = false;
            }

            DB::commit();
            $rpta = array(
                'respuesta' => $respuesta,
                'idCaja'    => $cajaVenta->id
            );
            
            return json_encode($rpta);

        } catch (\Exception $e) {
            DB::rollBack();
            return json_encode($e->getMessage());
        }

        
    }
    


}
