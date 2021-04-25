<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Greenter\Ws\Services\SunatEndpoints;
use Greenter\See;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use NumerosEnLetras;

// IMPRIMIT TICKET
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;


class sunat extends Controller
{
    public function villca()
    {
        // echo 'Formato #1 ' . NumerosEnLetras::convertir(8.00) . '/100 SOLES';


                
        $nombre_impresora = "POS"; 


        $connector = new WindowsPrintConnector($nombre_impresora);
        $printer = new Printer($connector);
        #Mando un numero de respuesta para saber que se conecto correctamente.
        echo 1;
        /*
            Vamos a imprimir un logotipo
            opcional. Recuerda que esto
            no funcionará en todas las
            impresoras

            Pequeña nota: Es recomendable que la imagen no sea
            transparente (aunque sea png hay que quitar el canal alfa)
            y que tenga una resolución baja. En mi caso
            la imagen que uso es de 250 x 250
        */

        # Vamos a alinear al centro lo próximo que imprimamos
        $printer->setJustification(Printer::JUSTIFY_CENTER);

        /*
            Intentaremos cargar e imprimir
            el logo
        */

        
        try{
            $logo = EscposImage::load(public_path('img/logo.png'), false);
            $printer->bitImage($logo);
        }catch(Exception $e){/*No hacemos nada si hay error*/}

        /*
            Ahora vamos a imprimir un encabezado
        */

        $printer->text("\n"."LA PRECIOSA " . "\n");
        $printer->text("Direccion: Orquídeas #151" . "\n");
        $printer->text("Tel: 454664544" . "\n");
        #La fecha también
        date_default_timezone_set("America/Mexico_City");
        $printer->text(date("Y-m-d H:i:s") . "\n");
        $printer->text("-----------------------------" . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("CANT  DESCRIPCION    P.U   IMP.\n");
        $printer->text("-----------------------------"."\n");
        /*
            Ahora vamos a imprimir los
            productos
        */
            /*Alinear a la izquierda para la cantidad y el nombre*/
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Producto Galletas\n");
            $printer->text( "2  pieza    10.00 20.00   \n");
            $printer->text("Sabrtitas \n");
            $printer->text( "3  pieza    10.00 30.00   \n");
            $printer->text("Doritos \n");
            $printer->text( "5  pieza    10.00 50.00   \n");
        /*
            Terminamos de imprimir
            los productos, ahora va el total
        */
        $printer->text("-----------------------------"."\n");
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text("SUBTOTAL: $100.00\n");
        $printer->text("IVA: $16.00\n");
        $printer->text("TOTAL: $116.00\n");


        /*
            Podemos poner también un pie de página
        */
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Muchas gracias por su compra\n");

        try{
            $logo = EscposImage::load(public_path('img/logo.png'), false);
            $printer->bitImage($logo);
        }catch(Exception $e){/*No hacemos nada si hay error*/}


        /*Alimentamos el papel 3 veces*/
        $printer->feed(3);

        /*
            Cortamos el papel. Si nuestra impresora
            no tiene soporte para ello, no generará
            ningún error
        */
        $printer->cut();

        /*
            Por medio de la impresora mandamos un pulso.
            Esto es útil cuando la tenemos conectada
            por ejemplo a un cajón
        */
        $printer->pulse();

        /*
            Para imprimir realmente, tenemos que "cerrar"
            la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
        */
        $printer->close();

    }

    public function villcas()
    {
        echo 'Formato #1 ' . NumerosEnLetras::convertir(8.00) . '/100 SOLES';
    }

    public function sunat()
    {
        $see = new See();
        $see->setService(SunatEndpoints::FE_BETA);
        $see->setCertificate(file_get_contents(public_path('\sunat\certificados\certificate.pem')));
        $see->setCredentials('20000000001MODDATOS'/*ruc+usuario*/, 'moddatos');

        
        // Cliente
        $client = new Client();
        $client->setTipoDoc('6')
            ->setNumDoc('20000000001')
            ->setRznSocial('EMPRESA 1');

        // Emisor
        $address = new Address();
        $address->setUbigueo('150101')
            ->setDepartamento('LIMA')
            ->setProvincia('LIMA')
            ->setDistrito('LIMA')
            ->setUrbanizacion('NONE')
            ->setDireccion('AV LS');

        $company = new Company();
        $company->setRuc('20000000001')
            ->setRazonSocial('EMPRESA SAC')
            ->setNombreComercial('EMPRESA')
            ->setAddress($address);

        // Venta
        $fechaActual = date('Y-m-d');
        $invoice = (new Invoice())
        ->setUblVersion('2.1')
        ->setTipoOperacion('0101') // Catalog. 51
        ->setTipoDoc('01')
        ->setSerie('F001')
        ->setCorrelativo('1')
        ->setFechaEmision(new \DateTime(date("d-m-Y H:i:s", strtotime($fechaActual))))
        ->setTipoMoneda('PEN')
        ->setClient($client)
        ->setMtoOperGravadas(100.00)
        ->setMtoIGV(18.00)
        ->setTotalImpuestos(18.00)
        ->setValorVenta(100.00)
        ->setMtoImpVenta(118.00)
        ->setCompany($company);

        $item = (new SaleDetail())
        ->setCodProducto('P001')
        ->setUnidad('KILOGRAMOS')
        ->setCantidad(2)
        ->setDescripcion('PRODUCTO 1')
        ->setMtoBaseIgv(100)
        ->setPorcentajeIgv(18.00) // 18%
        ->setIgv(18.00)
        ->setTipAfeIgv('10')
        ->setTotalImpuestos(18.00)
        ->setMtoValorVenta(100.00)
        ->setMtoValorUnitario(50.00)
        ->setMtoPrecioUnitario(59.00);

        $item2 = (new SaleDetail())
        ->setCodProducto('P001')
        ->setUnidad('KILOGRAMOS')
        ->setCantidad(2)
        ->setDescripcion('PRODUCTO 1')
        ->setMtoBaseIgv(100)
        ->setPorcentajeIgv(18.00) // 18%
        ->setIgv(18.00)
        ->setTipAfeIgv('10')
        ->setTotalImpuestos(18.00)
        ->setMtoValorVenta(100.00)
        ->setMtoValorUnitario(50.00)
        ->setMtoPrecioUnitario(59.00);


        $legend = (new Legend())
        ->setCode('1000')
        ->setValue('SON DOSCIENTOS TREINTA Y SEIS CON 00/100 SOLES');

        $invoice->setDetails([$item, $item2])
            ->setLegends([$legend]);

        $result = $see->send($invoice);

        // Guardar XML
        file_put_contents(public_path('\sunat\xml\s'.$invoice->getName().'.xml'),
                            $see->getFactory()->getLastXml());
        if (!$result->isSuccess()) {
        var_dump($result->getError());
        exit();
        }

        echo $result->getCdrResponse()->getDescription();
        // Guardar CDR
        file_put_contents(public_path('\sunat\zip\R-'.$invoice->getName().'.zip'), $result->getCdrZip()); 
    }
}
