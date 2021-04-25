<?php

namespace App\Http\Controllers\impresiones;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\cajasVentas;

use QrCode;

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class imprimirAperturaCajaController extends Controller
{
    public function aperturarCaja($idCajaVenta)
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
                                    'cajasVentas.observacionesApertura  as observacionesApertura'
                                ]);
                                

        $codigoQr = QrCode::format('png')
                            ->size(250)
                            ->generate(
                                $rucEmpresa."|".
                                $idCajaVenta."|".
                                $cajaVenta->numeroCajaVenta."|".
                                $cajaVenta->aperturaCajaVenta."|".
                                $cajaVenta->totalAperturaCajaVenta."|".
                                $cajaVenta->totalAperturoCajaVenta."|".
                                public_path('img\aperturaQr.png')
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
        

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("APERTURA DE CAJA: NUMERO: ".$cajaVenta->numeroCajaVenta."\n");
            $printer->text($cajaVenta->aperturaCajaVenta."\n");
            $printer->text("\n");
            $printer->text("SUCURSAL: ".$cajaVenta->nombreSucursal."\n");
            $printer->text("USUARIO QUE APERTURO: ".$cajaVenta->nombreUsuario."\n");
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
            $printer->text("\n");
            $imgQr = EscposImage::load(public_path('img\aperturaQr.png'), false);
            $printer->bitImage($imgQr);
            $printer->text("COPIA DE APERTURA DE CAJA (ENDULZATE)\n");
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
