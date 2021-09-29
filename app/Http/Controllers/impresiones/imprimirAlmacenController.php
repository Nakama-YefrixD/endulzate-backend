<?php

namespace App\Http\Controllers\impresiones;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\almacenes;
use QrCode;

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class imprimirAlmacenController extends Controller
{
    public function imprimirAlmacen($idUsuario)
    {
        // $idUsuario = $request->header('usuid');

    	if ($idUsuario == 1 || $idUsuario == 2) {
    		$sucursal = 1;
    	} else {
    		$sucursal = 2;
    	}

        date_default_timezone_set("America/Lima");
        $hoy = date("Y-m-d H:i:s");

        if($sucursal == 2){
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

        $alms = almacenes::join('productos as p', 'p.id', 'almacenes.producto_id')
                            ->where('sucursal_id', $sucursal)
                            ->where('almacenes.total', '!=', 0)
                            ->get([
                                'almacenes.id',
                                'p.codigo',
                                'p.nombre',
                                'p.precio',
                                'almacenes.stock',
                                'almacenes.vendido',
                                'almacenes.total',
                                'almacenes.transferenciarecibida',
                                'almacenes.transferenciarealizada',
                            ]);
        
        // IMPRIMIR TICKET
        $nombre_impresora = "POS";

        $connector = new WindowsPrintConnector($nombre_impresora);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);

        $printer->text("\n"." IMPRESIÓN DE STOCK DEL DÍA: "."\n");
        $printer->text("\n".$hoy."\n");
        $printer->text("\n".$nombreEmpresa."\n");
        $printer->text("Dirección: ".$direccionEmpresa . "\n");
        $printer->text("Tel: ".$telefonoEmpresa . "\n");
        $printer->text("\n");
        $printer->setJustification(Printer::JUSTIFY_CENTER);

        $printer->text("-----------------------------" . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("CANT  |  DESCRIPCION \n");
        $printer->text("-----------------------------"."\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);

        foreach($alms as $alm){

            $printer->text($alm->stock."  |  ".$alm->nombre.": \n");
            $printer->text("\n");
            $printer->text("-----------------------------"."\n");

        }

        $printer->feed(3);
        $printer->cut();
        $printer->pulse();
        $printer->close();

        return view('cerrar');

    }
}
