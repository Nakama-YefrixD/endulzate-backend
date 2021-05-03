<?php

namespace App\Http\Controllers\panel\cajaVenta;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\usuariosSucursales;
use App\cajasVentas;
use App\ventas;
use App\detallesVentas;
use App\notasCreditos;
use App\gastos;
use App\ingresosCajasVentas;


class cajaVentaController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        return view('react.app');
    }

    public function tb_cajaVenta(Request $request)
    {
        // $idUsuario          = $request['idUsuario'];
        // $idUsuario = auth()->id();
        $idUsuario = $request->header('usuid');
        $sucursalesUsuario  = usuariosSucursales::where('user_id', $idUsuario)
                                                    ->get(); 

        $cajaVenta = cajasVentas::join('sucursales as s', 's.id', 'cajasVentas.sucursal_id')
                                ->join('users as u', 'u.id', 'cajasVentas.user_id')
                                ->where(function ($query) use($sucursalesUsuario) {
                                    
                                    if(sizeof($sucursalesUsuario) > 0 ){
                                        foreach($sucursalesUsuario as $sucursalUsuario){
                                            $query->orWhere('s.id',  '=', $sucursalUsuario->sucursal_id);
    
                                        }
                                    }else{
                                        $query->where('s.id',  '=', 0);
                                    }
                                    
                                    
                                })
                                ->orderBy('cajasVentas.numero', 'desc')
                                ->paginate(
                                    10,
                                    array(
                                        'cajasVentas.id                     as  idCajaVenta',
                                        's.id',
                                        's.nombre                           as  nombreSurcursal',
                                        'u.name                             as  nombreUsuario',
                                        'cajasVentas.cierre                 as  cierreCajaVenta',
                                        'cajasVentas.numero                 as  numeroCajaVenta',
                                        'cajasVentas.apertura               as  aperturaCajaVenta',
                                        'cajasVentas.cierre                 as  cierreCajaVenta',
                                        'cajasVentas.totalApertura          as  totalAperturaCajaVenta',
                                        'cajasVentas.totalAperturo          as  totalAperturoCajaVenta',
                                        'cajasVentas.totalCierre            as  totalCierreCajaVenta',
                                        'cajasVentas.totalCerro             as  totalCerroCajaVenta',
                                        
                                        'cajasVentas.totalVentasTarjeta     as  totalVentasTarjetaCajaVenta',
                                        'cajasVentas.totalVentasEfectivo    as  totalVentasEfectivoCajaVenta',
                                        'cajasVentas.totalVentas            as  totalVentasCajaVenta',
                                        'cajasVentas.totalVentasCanceladas  as  totalVentasCanceladasCajaVenta',
                                        'cajasVentas.totalGastos            as  totalGastosCajaVenta',
                                        'cajasVentas.numeroVentasTarjeta    as  numeroVentasTarjetaCajaVenta',
                                        'cajasVentas.numeroVentasEfectivo   as  numeroVentasEfectivoCajaVenta',
                                        'cajasVentas.numeroVentas           as  numeroVentasCajaVenta',
                                        'cajasVentas.numeroGastos           as  numeroGastosCajaVenta',
                                        'cajasVentas.numeroItems            as  numeroItemsCajaVenta',
                                        'cajasVentas.numeroVentasCanceladas as  numeroVentasCanceladasCajaVenta',
                                        'cajasVentas.numeroItemsCancelados  as  numeroItemsCanceladosCajaVenta',
                                        'cajasVentas.created_at             as  created_atCajaVenta',
                                        'cajasVentas.estado                 as  estadoCajaVenta',
                                        'cajasVentas.observacionesApertura  as  observacionesAperturaCajaVenta',
                                        'cajasVentas.observacionesCierre    as  observacionesCierreCajaVenta',
                                        'cajasVentas.totalIngresos          as  totalIngresosCajaVenta',
                                        'cajasVentas.numeroIngresos         as  numeroIngresosCajaVenta'
                                    )
                                );

        if(sizeof($cajaVenta) > 0){
            $respuesta = true;
        }else{
            $respuesta = false;
        }
        
        $rpta = array(
            'respuesta'     => $respuesta,
            'tb_cajaVenta'  => $cajaVenta
        );
        
        return json_encode($rpta);


    }

    public function tb_cajaVenta_detallada(Request $request)
    {
        $idCajaVenta = $request['idCajaVenta'];

        $cajaVentaDetallada = array(
            array(
                'fecha'          => 0 ,
                'accion'         => 0 ,
                'numero'         => 0 ,
                'itemsVendidos'  => 0 ,
                'monto'          => 0 ,
                'montoMomento'   => 0 ,
                'operacion'         => 0 ,
            ),
        );


        $ventas = ventas::join('tiposcomprobantes   as tc', 'tc.id', 'ventas.tipoComprobante_id')
                        ->join('tiposmonedas        as tm', 'tm.id', 'ventas.tipoMoneda_id')
                        ->where('cajaVenta_id', $idCajaVenta)

                        ->get([
                            'ventas.id          as idVenta',
                            'ventas.created_at  as fechaCreadaVenta',
                            'tm.codigo          as codigoTipoMoneda',
                            'tc.nombre          as nombreTipoComprobante',
                            'tc.serie           as serieTipoComprobante',
                            'ventas.numero      as numeroVenta',
                            'ventas.total       as totalVenta'
                        ]);
        

        $cont = 0;
        
        if(sizeof($ventas) > 0 ){
            foreach($ventas as $venta){

                $cajaVentaDetallada[$cont]['fecha'] = $venta->fechaCreadaVenta;
    
                if($venta->codigoTipoMoneda != 71){
                    $cajaVentaDetallada[$cont]['accion'] = $venta->nombreTipoComprobante." - EFECTIVO";
                    $cajaVentaDetallada[$cont]['operacion'] = 1; // SUMA
                }else{
                    $cajaVentaDetallada[$cont]['accion'] = $venta->nombreTipoComprobante." - TARJETA";
                    $cajaVentaDetallada[$cont]['operacion'] = 2; // NEUTRAL
                }
    
                $cajaVentaDetallada[$cont]['numero'] = $venta->serieTipoComprobante."-".$venta->numeroVenta;
    
                $numeroItems = detallesVentas::where('venta_id', $venta->idVenta)
                                                ->sum('cantidad');
                
                $cajaVentaDetallada[$cont]['itemsVendidos'] = $numeroItems;
                $cajaVentaDetallada[$cont]['monto']         = $venta->totalVenta;
                $cajaVentaDetallada[$cont]['montoMomento']  = 0;
                $cont = $cont + 1;
    
            }
        }
        

        $notasCreditos = notasCreditos::join('ventas as v', 'v.id', 'notasCreditos.venta_id')
                                        ->join('tiposcomprobantes   as tc', 'tc.id', 'v.tipoComprobante_id')
                                        ->join('tiposmonedas        as tm', 'tm.id', 'v.tipoMoneda_id')                                
                                        ->where('notasCreditos.cajaVenta_id', $idCajaVenta)
                                        ->get([
                                            'notasCreditos.created_at as fechaCreadaNotaCredito',
                                            'tm.codigo              as codigoTipoMoneda',
                                            'tc.nombre              as nombreTipoComprobante',
                                            'tc.serie               as serieTipoComprobante',
                                            'v.id                   as idVenta',
                                            'v.numero               as numeroVenta',
                                            'v.total                as totalVenta'
                                        ]);
        if(sizeof($notasCreditos) > 0 ){
            foreach($notasCreditos as $notaCredito){

                $cajaVentaDetallada[$cont]['fecha']         = $notaCredito->fechaCreadaNotaCredito;
    
                if($notaCredito->codigoTipoMoneda != 71){
    
                    $cajaVentaDetallada[$cont]['accion'] = $notaCredito->nombreTipoComprobante." EFECTIVO - CANCELADA";
                    $cajaVentaDetallada[$cont]['operacion'] = 0; // RESTA
    
                }else{
    
                    $cajaVentaDetallada[$cont]['accion'] = $notaCredito->nombreTipoComprobante." TARJETA- CANCELADA";
                    $cajaVentaDetallada[$cont]['operacion'] = 2; // NEUTRAL
    
                }
                
                $cajaVentaDetallada[$cont]['numero'] = $notaCredito->serieTipoComprobante."-".$notaCredito->numeroVenta;
    
                $numeroItems = detallesVentas::where('venta_id', $notaCredito->idVenta)
                                                ->sum('cantidad');
    
                $cajaVentaDetallada[$cont]['itemsVendidos'] = $numeroItems;
                $cajaVentaDetallada[$cont]['monto']         = $notaCredito->totalVenta;
                $cajaVentaDetallada[$cont]['montoMomento']  = 0;
                $cont = $cont + 1;
    
            }
        }
        

        $gastos = gastos::where('cajaVenta_id', $idCajaVenta)
                        ->get([
                            'gastos.created_at  as fechaCreadaGasto',
                            'gastos.numero      as numeroGasto',
                            'gastos.gasto       as gastoGasto'
                        ]);
        if(sizeof($gastos) > 0){
            foreach($gastos as $gasto){
            
                $cajaVentaDetallada[$cont]['fecha']         = $gasto->fechaCreadaGasto;
                $cajaVentaDetallada[$cont]['accion']        = "GASTO";
                $cajaVentaDetallada[$cont]['numero']        = $gasto->numeroGasto;
                $cajaVentaDetallada[$cont]['itemsVendidos'] = 0;
                $cajaVentaDetallada[$cont]['monto']         = $gasto->gastoGasto;
                $cajaVentaDetallada[$cont]['montoMomento']  = 0;
                $cajaVentaDetallada[$cont]['operacion']     = 0;
                $cont = $cont + 1;
            }
        }
        

        $ingresos = ingresosCajasVentas::where('cajaVenta_id', $idCajaVenta)
                                        ->get([
                                            'ingresosCajasVentas.created_at as fechaCreadaIngreso',
                                            'ingresosCajasVentas.numero     as numeroIngreso',
                                            'ingresosCajasVentas.ingreso    as ingresoIngreso'
                                        ]);
        if(sizeof($ingresos) > 0){
            foreach($ingresos as $ingreso){
            
                $cajaVentaDetallada[$cont]['fecha']         = $ingreso->fechaCreadaIngreso;
                $cajaVentaDetallada[$cont]['accion']        = "INGRESO";
                $cajaVentaDetallada[$cont]['numero']        = $ingreso->numeroIngreso;
                $cajaVentaDetallada[$cont]['itemsVendidos'] = 0;
                $cajaVentaDetallada[$cont]['monto']         = $ingreso->ingresoIngreso;
                $cajaVentaDetallada[$cont]['montoMomento']  = 0;
                $cajaVentaDetallada[$cont]['operacion']     = 1;
                $cont = $cont + 1;
            }
        }

        usort($cajaVentaDetallada, function ($a, $b) {
            return strtotime($a['fecha']) > strtotime($b['fecha']);
        });

        $cajaVenta = cajasVentas::find($idCajaVenta);
        $nueva_cantidad = $cajaVenta->totalAperturo;

        for ($i=0; $i < count($cajaVentaDetallada); $i++) { 
            if ($cajaVentaDetallada[$i]['operacion'] == 0) {
                $cajaVentaDetallada[$i]['montoMomento'] = $nueva_cantidad - $cajaVentaDetallada[$i]['monto'];
            } elseif( $cajaVentaDetallada[$i]['operacion'] == 1 ) {
                $cajaVentaDetallada[$i]['montoMomento'] = $nueva_cantidad + $cajaVentaDetallada[$i]['monto'];
            } else{
                $cajaVentaDetallada[$i]['montoMomento'] = $nueva_cantidad;
            }

            $nueva_cantidad = $cajaVentaDetallada[$i]['montoMomento'];
        }

        $rpta = array(
            'respuesta'              => true,
            'tb_cajaVentaDetallada'  => $cajaVentaDetallada
        );
        
         dd($cajaVentaDetallada); 

    }

    public function cajaVenta($idUsuario)
    {
        // $idUsuario          = $request['idUsuario'];
        $sucursalesUsuario  = usuariosSucursales::where('user_id', $idUsuario)
                                                    ->get(); 

        $cajaVenta = cajasVentas::join('sucursales as s', 's.id', 'cajasVentas.sucursal_id')
                                ->where(function ($query) use($sucursalesUsuario) {
                                    
                                    if(sizeof($sucursalesUsuario) > 0 ){
                                        foreach($sucursalesUsuario as $sucursalUsuario){
                                            $query->orWhere('s.id',  '=', $sucursalUsuario->sucursal_id);
    
                                        }
                                    }else{
                                        $query->where('s.id',  '=', 0);
                                    }
                                    
                                    
                                })
                                ->get([
                                    's.id'
                                ]);
        // 
        // echo $sucursalesUsuario.'<br';                                
        echo $cajaVenta;


    }
}
