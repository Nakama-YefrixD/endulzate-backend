<?php

namespace App\Http\Controllers\prueba;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use QrCode;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

use App\ventas;
use App\clientes;
use App\detallesVentas;
use App\Tipos;
use App\control;
use App\marcas;
use App\notascreditos;
use App\productosEntradas;
use App\tiposComprobantes;
use App\tiposMonedas;
use App\almacenes;
use App\cajasproductos;
use App\tiposDocumentos;

use Greenter\Ws\Services\SunatEndpoints;
use Greenter\See;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use NumerosEnLetras;

use DB;
// NOTA DE CREDITO
use Greenter\Model\Sale\Note;
use Greenter\Model\Sale\Document;

use App\productos;
use App\proveedores;
use App\entradas;
use App\sucursales;
use App\usuariosSucursales;

class pruebaController extends Controller
{
    public function index()
    {
        $nombre_impresora = "POS"; 
            $codigoqr = QrCode::format('png')->size(500)->generate('hola', public_path('img/qr.png'));

            $connector = new WindowsPrintConnector($nombre_impresora);
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);

            
            try{
                $logo = EscposImage::load(public_path('img/logo.png'), false);
                $printer->bitImage($logo);
            }catch(Exception $e){/*No hacemos nada si hay error*/}


            $printer->text("\n"."LA PRECIOSA " . "\n");
            $printer->text("Direccion: Dean Valdivia 412 A" . "\n");
            $printer->text("Tel: 054 77 34 22" . "\n");
            #La fecha también
            
            $printer->text(date("Y-m-d H:i:s") . "\n");
            $printer->text("-----------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("CANT  DESCRIPCION    P.U   IMP.\n");
            $printer->text("-----------------------------"."\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
                
                

            $printer->text("-----------------------------"."\n");
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("SUBTOTAL: 2 \n");
            $printer->text("IVA: 2\n");
            $printer->text("TOTAL: 3\n");

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            
            $printer->text(QrCode::size(500)->generate('hola'));
            $printer->text("Muchas gracias por su compra\n");

            $printer->feed(3);
            $printer->cut();
            $printer->pulse();
            $printer->close();


            $codigoqr = QrCode::size(500)->generate('hola');
            
        return $codigoqr;
    }

    public function notaCredito($idVenta)
    {
        DB::beginTransaction();
        try {
            $ventas = ventas::select(   'td.codigo as codigoTiposdocumento', 'c.documento as documentoClientes',
                                        'c.nombre as nombreClientes', 'tc.codigo as codigoTiposcomprobante',
                                        'tc.serie as serieTiposcomprobante', 'ventas.numero as numeorVentas', 
                                        'ventas.fecha as fechaVentas', 'tm.abreviatura as abreviaturaTiposmoneda',
                                        'ventas.subtotal as subtotalVentas', 'ventas.impuestos as impuestosVentas',
                                        'ventas.total as totalVentas', 'ventas.id as idVentas'
                                    )
                            ->join('clientes as c', 'ventas.cliente_id', '=', 'c.id')            
                            ->join('tiposdocumento as td', 'c.tipoDocumento_id', '=', 'td.id')
                            ->join('tiposcomprobante as tc', 'ventas.tipoComprobante_id', '=', 'tc.id')
                            ->join('tiposmoneda as tm', 'ventas.tipoMoneda_id', '=', 'tm.id')
                            ->where('ventas.id', '=', $idVenta)
                            ->first();


            $see = new See();
            $see->setService(SunatEndpoints::FE_BETA);
            $see->setCertificate(file_get_contents(public_path('\sunat\certificadosfree\certificadofree.pem')));
            $see->setCredentials('20000000001MODDATOS', 'moddatos');

            if($ventas->documentoClientes == 0){
                $documentoCliente = "00000000";
            }else{
                $documentoCliente = $ventas->documentoClientes;
            }
                
            // Cliente
            $client = new Client();
            $client->setTipoDoc($ventas->codigoTiposdocumento) //6 es RUC
                ->setNumDoc($documentoCliente)
                ->setRznSocial($ventas->nombreClientes);

            // Emisor
            $address = new Address();
            $address->setUbigueo('040101')
                ->setDepartamento('AREQUIPA')
                ->setProvincia('AREQUIPA')
                ->setDistrito('AREQUIPA')
                ->setUrbanizacion('NONE')
                ->setDireccion('CAL. DEAN VALDIVIA 410, 412, 4 NRO. --');

            $company = new Company();
            $company->setRuc('20605007211')
                    ->setRazonSocial('LA PRECIOSA DISTRIBUCIONES IMPORTACIONES E.I.R.L')
                    ->setNombreComercial('PRECIOSA')
                    ->setAddress($address);

            $note = new Note();
            $note
                ->setUblVersion('2.1')
                ->setTipDocAfectado('01')
                ->setNumDocfectado('F001-111')
                ->setCodMotivo('07')
                ->setDesMotivo('DEVOLUCION POR ITEM')
                ->setTipoDoc('07')
                ->setSerie('FF01')
                ->setFechaEmision(new \DateTime())
                ->setCorrelativo('123')
                ->setTipoMoneda('PEN')
                ->setGuias([/* Guias (Opcional) */
                    (new Document())
                    ->setTipoDoc('09')
                    ->setNroDoc('001-213')
                ])
                ->setCompany($company)
                ->setClient($client)
                ->setMtoOperGravadas(200)
                ->setMtoIGV(36)
                ->setTotalImpuestos(36)
                ->setMtoImpVenta(236);

            $detail1 = new SaleDetail();
            $detail1
                ->setCodProducto('C023')
                ->setUnidad('NIU')
                ->setCantidad(2)
                ->setDescripcion('PROD 1')
                ->setMtoBaseIgv(100)
                ->setPorcentajeIgv(18.00)
                ->setIgv(18)
                ->setTipAfeIgv('10')
                ->setTotalImpuestos(18)
                ->setMtoValorVenta(100)
                ->setMtoValorUnitario(50)
                ->setMtoPrecioUnitario(56);
            $detail2 = new SaleDetail();
            $detail2
                ->setCodProducto('C02')
                ->setUnidad('NIU')
                ->setCantidad(2)
                ->setDescripcion('PROD 2')
                ->setMtoBaseIgv(100)
                ->setPorcentajeIgv(18.00)
                ->setIgv(18)
                ->setTipAfeIgv('10')
                ->setTotalImpuestos(18)
                ->setMtoValorVenta(100)
                ->setMtoValorUnitario(50)
                ->setMtoPrecioUnitario(56);
            $legend = new Legend();
            $legend->setCode('1000')
                ->setValue('SON DOSCIENTOS TREINTA Y SEIS CON 00/100 SOLES');
            $note->setDetails([$detail1, $detail2])
                ->setLegends([$legend]);
            
            $legend = (new Legend())
                ->setCode('1000')
                ->setValue(NumerosEnLetras::convertir($ventas->totalVentas).'/100 SOLES');



            // Envio a SUNAT.
            $res = $see->send($note);
            file_put_contents(
                public_path(
                    '\sunat\notaCredito\xml\venta-'.$ventas->idVentas.'-'.$note->getName().'.xml'
                ),
                $see->getFactory()->getLastXml()
            );

            if (!$res->isSuccess()) {
                var_dump($res->getError());
                exit();
            }

            /**@var $res \Greenter\Model\Response\BillResult*/

            file_put_contents(
                public_path(
                    '\sunat\notaCredito\zip\venta-'.$ventas->id.'-R-'.$note->getName().'.zip'
                ), 
                $res->getCdrZip()
            );

            // echo $res->getCdrResponse()->getDescription();



        }catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }

    public function pepe()
    {
        /*for ($id=1; Tipos::find($id) != null; $id++) {
            foreach (Tipos::where('id', $id)->get() as $fila) {
                Tipos::on('mysql2')->where('id', $id)->update([
                    'id'            =>$fila['id'],
                    'nombre'        =>$fila['nombre'],
                    'created_at'    =>$fila['created_at'],
                    'updated_at'    =>$fila['updated_at'],
                ]);
            }
        }
        if (Tipos::on('mysql2')->find(1) != null) {
            for ($id=1; Tipos::on('mysql2')->find($id) != null; $id++) {
                if (Tipos::on('mysql2')->find($id+1) == null) {
                    foreach (Tipos::where('id', '>', $id)->get() as $fila) {
                        Tipos::on('mysql2')->create([
                            'id'            =>$fila['id'],
                            'nombre'        =>$fila['nombre'],
                            'created_at'    =>$fila['created_at'],
                            'updated_at'    =>$fila['updated_at'],
                        ]);
                    }
                }
            }
        } else {
            foreach (Tipos::get() as $fila) {
                Tipos::on('mysql2')->create([
                    'id'            =>$fila['id'],
                    'nombre'        =>$fila['nombre'],
                    'created_at'    =>$fila['created_at'],
                    'updated_at'    =>$fila['updated_at'],
                        ]);
            }
        }*/
        $tablas = ["users","proveedores","tiposDocumentos","clientes","entradas","tiposMonedas","tipos","marcas","productos","tiposOperacion","controls","tiposComprobantes","ventas","detallesVentas","productosEntradas","notasCreditos","roles","role_user","permissions","permission_role","permission_user"];

        for ($cont=count($tablas)-1; $cont>=0; $cont--) {
            DB::connection('mysql2')->table($tablas[$cont])->delete();
        }
        
        foreach ($tablas as $tabla) {
            DB::table($tabla)->orderBy('id')->chunk(100, function ($datos) use($tabla) {
                foreach ($datos as $dato) {
                    foreach ($dato as $columna => $fila) {
                       if ($columna == 'id') {
                            $idColumna = $fila; 
                            DB::connection('mysql2')->table($tabla)->insert([
                                $columna => $fila,
                            ]);
                        } else {
                            DB::connection('mysql2')
                                ->table($tabla)
                                ->where('id', $idColumna)
                                ->update([
                                    $columna => $fila,
                            ]);
                        }
                    }
                }
            });
        }
    }

    public function hijoAbortado(Request $request)
    {
        if (count($request->sucursales) == 1) {

            $asignados = usuariosSucursales::where('user_id', $request->id)->get();

            if (count($asignados) > 1) {
                foreach ($asignados as $asignado) {

                    $existe = usuariosSucursales::where([
                        ['sucursal_id', $request->sucursales[0]],
                        ['user_id', $request->id]
                    ])->first();

                    if ($asignado->sucursal_id != $request->sucursales[0]) {
                        usuariosSucursales::where([
                            ['sucursal_id', $asignado->sucursal_id],
                            ['user_id', $request->id]
                        ])->delete();
                    }
                    if (!$existe) {
                        $crear = new usuariosSucursales;
                        $crear->sucursal_id = $request->sucursales[0];
                        $crear->user_id =  $request->id;
                        $crear->save();
                    } 
                }
            } else {
                $only_one = $asignados[0];
                $only_one->sucursal_id = $request->sucursales[0];
                $only_one->save();
            }

        } else if (count($request->sucursales) > 1) {

            $asignados = usuariosSucursales::where('user_id', $request->id)->get();

            foreach ($asignados as $asignado) {
                foreach ($request->sucursales as $sucursal) {

                    $existe = usuariosSucursales::where([
                        ['sucursal_id', $sucursal],
                        ['user_id', $request->id]
                    ])->first();

                    if (in_array($asignado->sucursal_id, $request->sucursales) == false) {
                        usuariosSucursales::where([
                            ['sucursal_id', $asignado->sucursal_id],
                            ['user_id', $request->id]
                        ])->delete(); 
                    }  
                    if (!$existe) {
                        $crear = new usuariosSucursales;
                        $crear->sucursal_id = $sucursal;
                        $crear->user_id =  $request->id;
                        $crear->save();
                    }
                }
            }
        }
    }

    public function hola()
    {
        $xd = "123";
        echo $xd;
        return $xd;

    }

    public function adios()
    {
        //$tiposDocumentos::get()

        return env('DB_CONNECTION');//config('variables_globales.tiposdocumentos');

    }

    public function productosalmacen()
    {
        // $productos = productos::all();

        // foreach($productos as $producto){
        //     $almacen = almacenes::where('producto_id', $producto->id)
        //                         ->first();
        //     if($almacen){
        //         $almacen->stock = $producto->cantidad;
        //         $almacen->total = $producto->cantidad;
        //         $almacen->update();
        //     }else{
        //         echo "no estan";
        //     }


        // }
        $producto = cajasproductos::where('producto_id', 5)
                                ->get();
        
        
        echo $producto->min('cantidad'); 

    }
};

