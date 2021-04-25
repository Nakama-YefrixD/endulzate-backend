<?php

namespace App\Http\Controllers\impresiones;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\cajasVentas;
use App\gastos;
use App\ingresoscajasventas;
use App\ventas;
use App\tiposmonedas;

use QrCode;

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class imprimirCierreCajaController extends Controller
{
    public function cierreCaja($idCajaVenta)
    {

        date_default_timezone_set("America/Lima");
        $rucEmpresa         = "2000001";
        $nombreEmpresa      = "ENDULZATE"; 
        $direccionEmpresa   = '-';
        $telefonoEmpresa    = '-';
        
        $cajaVenta = cajasVentas::join('sucursales as s', 's.id', 'cajasVentas.sucursal_id')
                                ->join('users as u', 'u.id', 'cajasVentas.user_id')
                                ->where('cajasVentas.id', $idCajaVenta)
                                ->first([
                                    'cajasVentas.numero         as numeroCajaVenta',
                                    'cajasVentas.apertura       as aperturaCajaVenta',
                                    's.nombre                   as nombreSucursal',
                                    'u.name                     as nombreUsuario',
                                    'cajasVentas.totalApertura  as totalAperturaCajaVenta',
                                    'cajasVentas.totalAperturo  as totalAperturoCajaVenta',
                                    'cajasVentas.observacionesApertura  as observacionesApertura',
                                    'cajasVentas.cierre         as cierreCajaVenta',
                                    'cajasVentas.totalCierre    as totalCierreCaja',
                                    'cajasVentas.totalCerro     as totalCerroCaja',
                                    'cajasVentas.totalGastos    as totalGastosCaja',
                                    'cajasVentas.numeroGastos   as numeroGastosCaja',
                                    'cajasVentas.totalIngresos  as totalIngresosCaja',
                                    'cajasVentas.numeroIngresos as numeroIngresosCaja',
                                    'cajasVentas.numeroVentasEfectivo as numeroVentasEfectivoCaja',
                                    'cajasVentas.totalVentasEfectivo  as totalVentasEfectivoCaja',

                                    'cajasVentas.observacionesCierre    as observacionesCierre',
                                    
                                    
                                    
                                     

                                ]);

        $tiposmonedas       = tiposmonedas::where('codigo', 71)->first();

        $gastos = gastos::where('cajaVenta_id', $idCajaVenta)
                         ->get();

        $ingresoscajasventas = ingresoscajasventas::where('cajaVenta_id', $idCajaVenta)
                                                    ->get();
        
        $ventas = ventas::where('cajaVenta_id', $idCajaVenta)
                          ->where('tipoMoneda_id', '!=',$tiposmonedas->id)
                          ->get();

        $codigoQr = QrCode::format('png')
                            ->size(250)
                            ->generate(
                                $rucEmpresa."|".
                                $idCajaVenta."|".
                                $cajaVenta->numeroCajaVenta."|".
                                $cajaVenta->aperturaCajaVenta."|".
                                $cajaVenta->totalAperturaCajaVenta."|".
                                $cajaVenta->totalAperturoCajaVenta."|".
                                public_path('img\cierreQr.png')
                            );

        // IMPRIMIR TICKET
        $nombre_impresora = "POS";

        $connector = new WindowsPrintConnector($nombre_impresora);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);

        // try{
        //     $logo = EscposImage::load(public_path('/img/logo.png'), false);
        //     $printer->bitImage($logo);
        // }catch(Exception $e){ return $e;/*No hacemos nada si hay error*/}

        $printer->text("\n".$nombreEmpresa."\n");
        $printer->text("\n");

        
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("APERTURA DE CAJA: NUMERO: ".$cajaVenta->numeroCajaVenta."\n");
        $printer->text($cajaVenta->aperturaCajaVenta."\n");
        $printer->text("\n");
        $printer->text("-----------------------------"."\n");
        $printer->text("\n");
        $printer->text("SUCURSAL: ".$cajaVenta->nombreSucursal."\n");
        $printer->text("USUARIO QUE APERTURO: ".$cajaVenta->nombreUsuario."\n");
        $printer->text("\n");
        //----------------------------------------------------------------
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("DINERO CON EL QUE DEBERIA APERTURAR: ");
        // $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text($cajaVenta->totalAperturaCajaVenta."\n");
        //---------------------------------------------------------------- 
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("DINERO CON EL QUE APERTURO: ");
        // $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text($cajaVenta->totalAperturoCajaVenta."\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("DIFERENCIA DE APERTURA: ");
        // $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $diferenciaApertura = $cajaVenta->totalAperturaCajaVenta - $cajaVenta->totalAperturoCajaVenta;
        $printer->text($diferenciaApertura."\n");
        $printer->text("\n");
        //---------------------------------------------------------------- 
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("COMENTARIO O OBSERVACION DE LA APERTURA \n");
        $printer->text($cajaVenta->observacionesApertura."\n");
        $printer->text("\n");
        $printer->text("-----------------------------"."\n");
        $printer->text("\n");

        //-----------------------------     GASTOS    ----------------------------------- //
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("GASTOS REALIZADOS: \n");
        $printer->text("\n");
        
        if(sizeof($gastos) > 0){
            $cont = 0;
            foreach($gastos as $gasto){
                
                $printer->text("GASTO NUMERO: ".$gasto->numero."\n");
                $printer->text($gasto->created_at."\n");
                $printer->text("MOTIVO: ".$gasto->motivo."\n");
                $printer->text("TOTAL: ".$gasto->gasto."\n");

                $cont = $cont + 1;
            }
        }else{
            $cont = 0;
            $printer->text("NO SE REALIZO NINGUN GASTO: \n");
        }

        $printer->text("\n");
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text("NUMERO: ".$cajaVenta->numeroGastosCaja."\n");
        $printer->text("TOTAL: ".$cajaVenta->totalGastosCaja."\n");
        $printer->text("\n");

        //-----------------------------     INGRESOS    ----------------------------------- //
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("\n");
        $printer->text("-----------------------------"."\n");
        $printer->text("\n");
        $printer->text("INGRESOS REALIZADOS: \n");
        $printer->text("\n");

        if(sizeof($ingresoscajasventas) > 0){
            $cont = 0;
            foreach($ingresoscajasventas as $ingresocajaventa){
                
                $printer->text("INGRESO NUMERO: ".$ingresocajaventa->numero."\n");
                $printer->text($ingresocajaventa->created_at."\n");
                $printer->text("MOTIVO: ".$ingresocajaventa->motivo."\n");
                $printer->text("TOTAL: ".$ingresocajaventa->ingreso."\n");

                $cont = $cont + 1;
            }
        }else{
            $printer->text("NO SE REALIZO NINGUN INGRESO: \n");
        }

        $printer->text("\n");
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text("NUMERO: ".$cajaVenta->numeroIngresosCaja."\n");
        $printer->text("TOTAL: ".$cajaVenta->totalIngresosCaja."\n");
        $printer->text("\n");

        

        //-----------------------------     INGRESOS    ----------------------------------- //
        
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("\n");
        $printer->text("-----------------------------"."\n");
        $printer->text("\n");
        $printer->text("VENTAS REALIZADAS: \n");
        $printer->text("\n");

        if(sizeof($ventas) > 0){
            
            foreach($ventas as $venta){
                
                $printer->text("VENTA NUMERO: ".$venta->numero."\n");
                $printer->text("OBSERVACION: ".$venta->Observaciones."\n");
                $printer->text("TOTAL: ".$venta->total."\n");

                $cont = $cont + 1;
            }
        }else{
            $printer->text("NO SE REALIZO NINGUNA VENTA: \n");
        }

        $printer->text("\n");
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text("NUMERO: ".$cajaVenta->numeroVentasEfectivoCaja."\n");
        $printer->text("TOTAL: ".$cajaVenta->totalVentasEfectivoCaja."\n");
        $printer->text("\n");
        

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("\n");
        $printer->text("-----------------------------"."\n");
        $printer->text("\n");
        $printer->text("CIERRE DE CAJA: NUMERO: ".$cajaVenta->numeroCajaVenta."\n");
        $printer->text($cajaVenta->cierreCajaVenta."\n");
        $printer->text("\n");
        $printer->text("-----------------------------"."\n");
        $printer->text("\n");
        $printer->text("SUCURSAL: ".$cajaVenta->nombreSucursal."\n");
        $printer->text("USUARIO QUE CERRO: ".$cajaVenta->nombreUsuario."\n");
        $printer->text("\n");
        //----------------------------------------------------------------
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("DINERO CON EL QUE DEBERIA CERRAR: ");
        // $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text($cajaVenta->totalCierreCaja."\n");
        //---------------------------------------------------------------- 
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("DINERO CON EL QUE CERRO: ");
        // $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text($cajaVenta->totalCerroCaja."\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("DIFERENCIA DE APERTURA: ");
        // $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $diferenciaCierre = $cajaVenta->totalCierreCaja - $cajaVenta->totalCerroCaja;
        $printer->text($diferenciaCierre."\n");
        $printer->text("\n");
        //---------------------------------------------------------------- 
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("COMENTARIO O OBSERVACION DEL CIERRE DE CAJA \n");
        $printer->text($cajaVenta->observacionesCierre."\n");
        


        $printer->text("\n");
        $printer->text("\n");
        $imgQr = EscposImage::load(public_path('img\cierreQr.png'), false);
        $printer->bitImage($imgQr);
        $printer->text("COPIA DE CIERRE DE CAJA (ENDULZATE)\n");
        $printer->feed(3);
        $printer->cut();
        $printer->pulse();
        $printer->close();
        
        $rpta = array(
            'respuesta' => true,
        );
        
        return json_encode($rpta);
        
    }
}
