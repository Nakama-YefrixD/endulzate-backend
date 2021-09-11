<?php

namespace App\Http\Controllers\impresiones;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\transferencias;
use QrCode;

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class imprimirTransferenciaController extends Controller
{
    public function imprimirTransferencia($idTransferencia)
    {

        date_default_timezone_set("America/Lima");
        $hoy = date("Y-m-d H:i:s");

        $tran = transferencias::join('sucursales as so', 'so.id', 'transferencias.origen')
                                ->join('sucursales as sd', 'sd.id', 'transferencias.destino')
                                ->join('productos as p', 'p.id', 'transferencias.producto_id')
                                ->where('transferencias.id', $idTransferencia)
                                ->first([
                                    'transferencias.id',
                                    'so.nombre as origennombre',
                                    'sd.nombre as destinonombre',
                                    'p.nombre as productonombre',
                                    'transferencias.cantidad',
                                    'transferencias.motivo',
                                    'transferencias.antesOrigenCantidad',
                                    'transferencias.antesDestinoCantidad',
                                    'transferencias.despuesOrigenCantidad',
                                    'transferencias.despuesDestinoCantidad',
                                    'transferencias.fechaEnvio',
                                ]);


        // IMPRIMIR TICKET
        $nombre_impresora = "POS";

        $connector = new WindowsPrintConnector($nombre_impresora);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);

        $printer->text("\n"." IMPRESIÓN DE ALMACEN: "."\n");
        $printer->text("\n".$hoy."\n");
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("-----------------------------" . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("ALMACEN NUMERO: ");
        $printer->text($tran->id."\n");
        $printer->text("-----------------------------" . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("TIENDA DE ORIGEN: ");
        // $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text($tran->origennombre."\n");
        $printer->text("-----------------------------" . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("TIENDA DE DESTINO: ");
        $printer->text($tran->destinonombre."\n");

        $printer->text("-----------------------------" . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("PRODUCTO TRANSFERIDO: ");
        $printer->text($tran->productonombre."\n");
        
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("CANTIDAD: ");
        $printer->text($tran->cantidad."\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("FECHA DE TRANSFERENCIA: ");
        $printer->text($tran->fechaEnvio."\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("MOTIVO: ");
        $printer->text($tran->motivo."\n");

        $printer->text("-----------------------------" . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("OTROS DATOS:\n");
        $printer->text("-----------------------------" . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("TIENDA DE ORIGEN CANTIDAD ANTERIOR: ");
        $printer->text($tran->antesOrigenCantidad."\n");
        $printer->text("-----------------------------" . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("TIENDA DE DESTINO CANTIDAD ANTERIOR: ");
        $printer->text($tran->antesDestinoCantidad."\n");

        $printer->text("-----------------------------" . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("TIENDA DE ORIGEN NUEVA CANTIDAD: ");
        $printer->text($tran->despuesOrigenCantidad."\n");

        $printer->text("-----------------------------" . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("TIENDA DE DESTINO NUEVA CANTIDAD: ");
        $printer->text($tran->despuesDestinoCantidad."\n");

        $printer->feed(3);
        $printer->cut();
        $printer->pulse();
        $printer->close();
    }

}
