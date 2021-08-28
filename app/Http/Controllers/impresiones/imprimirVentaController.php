<?php

namespace App\Http\Controllers\impresiones;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\ventas;
use App\detallesVentas;
use App\productos;
use QrCode;

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class imprimirVentaController extends Controller
{
    public function imprimirVenta($idVenta)
    {
        date_default_timezone_set("America/Lima");
        $tipoventa = "EFECTIVO";

        $ventas = ventas::join('clientes as c', 'ventas.cliente_id', '=', 'c.id')
                        ->join('tiposDocumentos as td', 'c.tipoDocumento_id', '=', 'td.id')
                        ->join('tiposComprobantes as tc', 'ventas.tipoComprobante_id', '=', 'tc.id')
                        ->join('tiposMonedas as tm', 'ventas.tipoMoneda_id', '=', 'tm.id')
                        ->where('ventas.id', '=', $idVenta)
                        ->first([
                            'td.codigo          as codigoTiposdocumento',
                            'td.abreviacion     as abreviacionTiposdocumento',
                            'c.documento        as documentoClientes',
                            'c.nombre           as nombreClientes',
                            'c.direccion        as direccionClientes',
                            'tc.codigo          as codigoTiposcomprobante',
                            'tc.serie           as serieTiposcomprobante',
                            'tc.correlativo     as correlativoTiposcomprobante',
                            'tc.nombre          as nombreTiposcomprobante',
                            'ventas.numero      as numeroVentas',
                            'ventas.fecha       as fechaVentas',
                            'tm.abreviatura     as abreviaturaTiposmoneda',
                            'ventas.subtotal    as subtotalVentas',
                            'ventas.impuestos   as impuestosVentas',
                            'ventas.total       as totalVentas',
                            'ventas.id          as idVentas',
                            'ventas.created_at  as created_atVenta',
                            'ventas.sucursal_id as sucursal_id',
                            'ventas.tipoMoneda_id'
                        ]);

        if($ventas->sucursal_id == 2){
            $rucEmpresa         = "10416379179";
            $nombreEmpresa      = "ENDULZATE 2"; 
            $direccionEmpresa   = 'Calle Alfonso Ugarte 313 a, Urb La Libertad C.C.';
            $telefonoEmpresa    = '054-259909';
        }else{
            $rucEmpresa         = "10416379179";
            $nombreEmpresa      = "ENDULZATE 1"; 
            $direccionEmpresa   = 'Calle Marañon 324 Zamacola';
            $telefonoEmpresa    = '054-316354';
        }


        if($ventas->documentoClientes == 0){
            $documentoCliente = "00000000";
        }else{
            $documentoCliente = $ventas->documentoClientes;
        }

        if($ventas->tipoMoneda_id == 3){
            $tipoventa = "TARJETA";
        }

        $codigoQr = QrCode::format('png')
                            ->size(250)
                            ->generate(
                                $rucEmpresa."|".
                                $ventas->codigoTiposcomprobante."|".
                                $ventas->serieTiposcomprobante."|".
                                $ventas->numeroVentas."|".
                                $ventas->impuestosVentas."|".
                                $ventas->totalVentas."|".
                                $ventas->fechaVentas."|".
                                $ventas->codigoTiposdocumento."|".
                                $documentoCliente."|",
                                public_path('img/qr.png')
                            );


        // IMPRIMIR TICKET
        $nombre_impresora = "POS";

        $connector = new WindowsPrintConnector($nombre_impresora);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);


        // try{
        //     $logo = EscposImage::load(public_path('/img/logo.png'), false);
        //     $printer->bitImage($logo);
        // }catch(Exception $e){/*No hacemos nada si hay error*/}

        $printer->text("\n".$nombreEmpresa."\n");
        $printer->text("\n".$rucEmpresa."\n");
        $printer->text("Dirección: ".$direccionEmpresa . "\n");
        $printer->text("Tel: ".$telefonoEmpresa . "\n");
        $printer->text("\n");
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text($ventas->nombreTiposcomprobante."\n");
        $printer->text("SERIE: ".$ventas->serieTiposcomprobante."-".$ventas->numeroVentas."\n");
        $printer->text("TIPO: ".$tipoventa."\n");
        #La fecha tambi�n

        $printer->text($ventas->created_atVenta . "\n");
        $printer->text("\n");
        $printer->text("Señor(es): ".$ventas->nombreClientes."\n");
        $printer->text("Dirección: ".$ventas->direccionClientes."\n");
        $printer->text($ventas->abreviacionTiposdocumento.": ".$documentoCliente."\n");
        $printer->text("-----------------------------" . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("CANT  DESCRIPCION    P.U   IMP.\n");
        $printer->text("-----------------------------"."\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);

        $ventaDetalles = detallesVentas::where('venta_id', $ventas->idVentas)
                                        ->get();
        foreach($ventaDetalles as $ventaDetalle){
            $producto = productos::where('id', $ventaDetalle->producto_id)
                                    ->first();

            $printer->text($producto->nombre.": \n");
            $precio = $producto->precio - $ventaDetalle->descuento;
            $printer->text( $ventaDetalle->cantidad."  unidad    ".$precio." ".$ventaDetalle->total."   \n");

        }


        $printer->text("-----------------------------"."\n");
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text("SUBTOTAL: ".$ventas->subtotalVentas."\n");
        $printer->text("IGV: ".$ventas->impuestosVentas."\n");
        $printer->text("TOTAL: ".$ventas->totalVentas."\n");
        $printer->text("\n");

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        // $printer->text("Puede consultar en preciosaweb.com (https://preciosaweb.com/consultas)"."\n");
        // $printer->text("\n");
        $printer->text("\n");
        $imgQr = EscposImage::load(public_path('img/qr.png'), false);
        $printer->bitImage($imgQr);
        $printer->text("Muchas gracias por su compra\n");

        $printer->feed(3);
        $printer->cut();
        $printer->pulse();
        $printer->close();



    }

    // EN DESARROLLO LA NOTA DE CREDITO
    public function imprimirNotaCredito()
    {
        date_default_timezone_set("America/Lima");
        $rucEmpresa         = "2000001";
        $nombreEmpresa      = "ENDULZATE"; 
        $direccionEmpresa   = '-';
        $telefonoEmpresa    = '-';

        $ventas = ventas::join('clientes as c', 'ventas.cliente_id', '=', 'c.id')
                        ->join('tiposDocumento as td', 'c.tipoDocumento_id', '=', 'td.id')
                        ->join('tiposComprobante as tc', 'ventas.tipoComprobante_id', '=', 'tc.id')
                        ->join('tiposMoneda as tm', 'ventas.tipoMoneda_id', '=', 'tm.id')
                        ->where('ventas.id', '=', $idVenta)
                        ->first([
                            'td.codigo          as codigoTiposdocumento',
                            'td.abreviacion     as abreviacionTiposdocumento',
                            'c.documento        as documentoClientes',
                            'c.nombre           as nombreClientes',
                            'c.direccion        as direccionClientes',
                            'tc.codigo          as codigoTiposcomprobante',
                            'tc.serie           as serieTiposcomprobante',
                            'tc.correlativo     as correlativoTiposcomprobante',
                            'tc.nombre          as nombreTiposcomprobante',
                            'ventas.numero      as numeroVentas',
                            'ventas.fecha       as fechaVentas',
                            'tm.abreviatura     as abreviaturaTiposmoneda',
                            'ventas.subtotal    as subtotalVentas',
                            'ventas.impuestos   as impuestosVentas',
                            'ventas.total       as totalVentas',
                            'ventas.id          as idVentas',
                            'ventas.created_at  as created_atVenta'
                        ]);

        if($ventas->documentoClientes == 0){
            $documentoCliente = "00000000";
        }else{
            $documentoCliente = $ventas->documentoClientes;
        }

        $codigoQr = QrCode::format('png')
                            ->size(250)
                            ->generate(
                                $rucEmpresa."|".
                                $ventas->codigoTiposcomprobante."|".
                                $ventas->serieTiposcomprobante."|".
                                $ventas->numeroVentas."|".
                                $ventas->impuestosVentas."|".
                                $ventas->totalVentas."|".
                                $ventas->fechaVentas."|".
                                $ventas->codigoTiposdocumento."|".
                                $documentoCliente."|",
                                public_path('img/qr.png')
                            );


        // IMPRIMIR TICKET
        $nombre_impresora = "POS";

        $connector = new WindowsPrintConnector($nombre_impresora);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);


        try{
            $logo = EscposImage::load(public_path('/img/logo.png'), false);
            $printer->bitImage($logo);
        }catch(Exception $e){/*No hacemos nada si hay error*/}

        $printer->text("\n".$nombreEmpresa."\n");
        $printer->text("\n".$rucEmpresa."\n");
        $printer->text("Dirección: ".$direccionEmpresa . "\n");
        $printer->text("Tel: ".$telefonoEmpresa . "\n");
        $printer->text("\n");
        

        $printer->text("NOTA DE CREDITO"."\n");
        $printer->text("SERIE: ".$ventas->serieTiposcomprobante."-".$ventas->numeroVentas."\n");
        #La fecha tambi�n
        $printer->text("2019-11-14 16:29:28". "\n");

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("\n");
        $printer->text("PARA LA: "."\n");
        $printer->text($ventas->nombreTiposcomprobante."\n");
        $printer->text("SERIE: ".$ventas->serieTiposcomprobante."-".$ventas->numeroVentas."\n");
        #La fecha tambi�n
        $printer->text($ventas->created_atVenta . "\n");
        $printer->text("\n");
        $printer->text("Señor(es): ".$ventas->nombreClientes."\n");
        $printer->text("Dirección: ".$ventas->direccionClientes."\n");
        $printer->text($ventas->abreviacionTiposdocumento.": ".$documentoCliente."\n");
        
        $printer->text("\n");
        $printer->text("MOTIVO: \n");
        $printer->text("RUC INCORRECTO \n");

        $printer->text("-----------------------------" . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("CANT  DESCRIPCION    P.U   IMP.\n"); 
        $printer->text("-----------------------------"."\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);

        $ventaDetalles = detallesVentas::where('venta_id', $ventas->idVentas)
                                        ->get();
        foreach($ventaDetalles as $ventaDetalle){
            $producto = productos::where('id', $ventaDetalle->producto_id)
                                    ->first();

            $printer->text($producto->nombre.": \n");
            $precio = $producto->precio - $ventaDetalle->descuento;
            $printer->text( $ventaDetalle->cantidad."  unidad    ".$precio." ".$ventaDetalle->total."   \n");

        }


        $printer->text("-----------------------------"."\n");
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text("SUBTOTAL: ".$ventas->subtotalVentas."\n");
        $printer->text("IGV: ".$ventas->impuestosVentas."\n");
        $printer->text("TOTAL: ".$ventas->totalVentas."\n");
        $printer->text("\n");

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Puede consultar en preciosaweb.com (https://preciosaweb.com/consultas)"."\n");
        $printer->text("\n");
        $printer->text("\n");
        $imgQr = EscposImage::load(public_path('img/qr.png'), false);
        $printer->bitImage($imgQr);
        $printer->text("Muchas gracias por su compra\n");

        $printer->feed(3);
        $printer->cut();
        $printer->pulse();
        $printer->close();


    }

    

}
