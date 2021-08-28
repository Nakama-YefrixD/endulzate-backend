<?php

namespace App\Http\Controllers\panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ventas;
use App\clientes;
use App\detallesVentas;
use App\tiposComprobantes;
use App\tiposDocumentos;
use App\tiposMonedas;
use App\productos;
use App\control;
use App\User;
use App\notascreditos;
use App\almacenes;
use App\cajasVentas;
use Yajra\DataTables\DataTables;

use Greenter\Ws\Services\SunatEndpoints;
use Greenter\See;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use NumerosEnLetras;

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

use DB;
use PDF;
use QrCode;
use Response;

// NOTA DE CREDITO
use Greenter\Model\Sale\Note;
use Greenter\Model\Sale\Document;

class ventasController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        // return view('ventas.index');
        return view('react.app');
    }

    public function tb_ventas(Request $request)
    {
        $idUsuario = $request->header('usuid');

    	if ($idUsuario == 1 || $idUsuario == 2) {
    		$idSucursal = 1;
    	} else {
    		$idSucursal = 2;
    	}

        return ventas::join('tiposComprobantes', 'ventas.tipoComprobante_id', '=', 'tiposComprobantes.id')
                        ->join('clientes', 'ventas.cliente_id', '=', 'clientes.id')
                        ->where('ventas.sucursal_id', $idSucursal)
                        ->where(function ($query) use($request) {
                            if($request->get('bcliente') != '') {
                                $query->where('clientes.nombre', 'like', '%' . $request->get('bcliente') . '%');
                            }

                            if($request->get('bcomprobante') != '') {
                                $query->where('tiposComprobantes.nombre', 'like', '%' . $request->get('bcomprobante') . '%');
                            }

                            if($request->get('bnumeroComprobante') != '') {
                                $query->where('ventas.numero',   $request->get('bnumeroComprobante') );
                            }

                            if($request->get('dateOne') != '') {
                                $query->whereBetween('ventas.fecha',  [$request->get('dateOne'), $request->get('dateTwo')]);
                            }
                        })
                        ->orderBy('ventas.numero', 'desc')
                        ->select(
                            'ventas.id                  as idVentas',
                            'tiposComprobantes.id       as idTiposcomprobante',
                            'tiposComprobantes.nombre   as nombreTiposcomprobante',
                            'ventas.fecha               as fechaVentas',
                            'clientes.nombre            as nombreClientes',
                            'ventas.numero              as numeroVentas',
                            'ventas.estadoSunat         as estadoSunatVentas',
                            'ventas.subtotal            as subTotalVentas',
                            'ventas.total               as totalVentas',
                            'ventas.tipoMoneda_id'
                            
                        )->paginate(10);
    }

    public function tb_ventas_detalladas(Request $request)
    {
        $idUsuario = $request->header('usuid');

    	if ($idUsuario == 1 || $idUsuario == 2) {
    		$idSucursal = 1;
    	} else {
    		$idSucursal = 2;
    	}

        return ventas::join('tiposComprobantes', 'ventas.tipoComprobante_id', '=', 'tiposComprobantes.id')
                        ->join('detallesVentas as dv', 'dv.venta_id', 'ventas.id')
                        ->join('productos as p', 'p.id', 'dv.producto_id')
                        ->join('sucursales as s', 's.id', 'ventas.sucursal_id')
                        ->where('ventas.sucursal_id', $idSucursal)
                        ->where(function ($query) use($request) {

                            if($request->get('bcodProduct') != '') {
                                $query->where('p.codigo', $request->get('bcodProduct'));
                            }

                            if($request->get('bnumeroComprobante') != '') {
                                $query->where('ventas.numero',   $request->get('bnumeroComprobante') );
                            }

                            if($request->get('dateOne') != '' && $request->get('dateTwo') != '') {
                                $query->whereBetween('ventas.fecha',  [$request->get('dateOne'), $request->get('dateTwo')]);
                            }
                        })
                        ->orderBy('ventas.numero', 'desc')
                        ->select(
                            'ventas.id                  as idVentas',
                            'dv.id                      as idDetalleVenta',
                            's.nombre                   as nombreSucursal',
                            'ventas.fecha               as fechaVentas',
                            'tiposComprobantes.nombre   as nombreTiposcomprobante',
                            'ventas.numero              as numeroVentas',
                            'p.codigo                   as codigoProducto',
                            'p.nombre                   as nombreProducto',
                            'p.precio                   as precioProducto',
                            'dv.cantidad                as cantidadDetalleVenta',
                            'dv.descuento               as descuentoDetalleVenta',
                            'dv.subtotal                as subtotalDetalleVenta',
                            'dv.total                   as totalDetalleVenta'
                        )->paginate(10);
    }

    public function tb_buscarProducto(Request $request)
    {
        $idUsuario = $request->header('usuid');
        if($idUsuario == 1 || $idUsuario == 2){
            // $idSucursal                  = env('sucursalId');
            $idSucursal = 1;
        }else{
            $idSucursal = 2;
        }
        return productos::leftjoin('descuentosProductos as dp', 'dp.producto_id', '=','productos.id')
                                ->join('marcas as m', 'm.id', '=', 'productos.marca_id')
                                ->join('tipos as t', 't.id', '=', 'productos.tipo_id')
                                ->join('almacenes', 'productos.id', '=', 'almacenes.producto_id')
                                ->where('almacenes.sucursal_id', $idSucursal)
                                ->where(function ($query) use($request) {
                                    if($request->get('bcodigo') != '') {
                                        $query->where('productos.codigo', 'like', '%' . $request->get('bcodigo') . '%');
                                    }

                                    if($request->get('bmarca') != '') {
                                        $query->where('m.nombre', 'like', '%' . $request->get('bmarca') . '%');
                                    }

                                    if($request->get('btipo') != '') {
                                        $query->where('t.nombre', 'like', '%' . $request->get('btipo') . '%');
                                    }

                                    if($request->get('bnombre') != '') {
                                        $query->where('productos.nombre', 'like', '%' . $request->get('bnombre') . '%');
                                    }

                                    if($request->get('bprecio') != '') {
                                        $query->where('productos.precio', 'like', '%' . $request->get('bprecio') . '%');
                                    }
                                })
                                ->select(
                                    'productos.codigo     as codigoProducto',
                                    'm.nombre             as marcaProducto',
                                    't.nombre             as tipoProducto',
                                    'almacenes.stock      as disponiblesProducto',
                                    'productos.nombre     as nombreProducto',
                                    'productos.precio     as precioProducto',
                                    'productos.id         as idProducto',
                                    'dp.producto_id       as idProductoDescuento',
                                    'dp.porcentaje        as porcentajeProductoDescuento',
                                    'dp.cantidad          as cantidadProductoDescuento'
                                )->paginate(10);
    }

    public function emitirFactura(Request $request)
    {
        date_default_timezone_set("America/Lima");
        $fechaActual = date('Y-m-d');
        // ENVIAR A LA SUNAT
        $see = new See();
        $see->setService(SunatEndpoints::FE_BETA);
        $see->setCertificate(file_get_contents(public_path('\sunat\certificadosfree\certificadofree.pem')));
        $see->setCredentials('20000000001MODDATOS'/*ruc+usuario*/, 'moddatos');
        // ---------- FACTURACION -------------
        $tipoDocumento    = tiposDocumentos::find($request['tipoDocumento']);
        $tiposcomprobante = tiposComprobantes::find($request['tipoComprobante']);
        $tipoMoneda       = tiposMonedas::find($request['tipoMoneda']);

        // Cliente
        $client = new Client();
        $client->setTipoDoc($tipoDocumento->codigo) //6 es RUC
            ->setNumDoc($request['numeroDocumento'])
            ->setRznSocial($request['razonSocial']);

        // Emisor
        $address = new Address();
        $address->setUbigueo('040101')
            ->setDepartamento('AREQUIPA')
            ->setProvincia('AREQUIPA')
            ->setDistrito('AREQUIPA')
            ->setUrbanizacion('NONE')
            ->setDireccion('CAL. DEAN Cerro 410, 412, 4 NRO. --');

        $company = new Company();
        $company->setRuc('20000000001')
                ->setRazonSocial('LA EMPRESA DISTRIBUCIONES IMPORTACIONES E.I.R.L')
                ->setNombreComercial('EMPRESA')
                ->setAddress($address);


        DB::beginTransaction();
        try {

            $cliente = clientes::where('documento', $request['numeroDocumento'])
                                ->first();

            if($cliente){
                $idCliente = $cliente->id;
            }else{
                $cliente = new clientes;
                $cliente->tipoDocumento_id  = $request['tipoDocumento'];
                $cliente->documento         = $request['numeroDocumento'];
                $cliente->nombre            = $request['razonSocial'];
                $cliente->direccion         = $request['direccion'];
                $cliente->save();

                $idCliente = $cliente->id;
            }

            $venta = new ventas;
            $venta->tipoComprobante_id  = $request['tipoComprobante'];
            $venta->cliente_id          = $idCliente;
            $venta->user_id             = $request->header('usuid');
            $venta->tipoMoneda_id       = $request['tipoMoneda'];
            $venta->numero              = $request['facturaVenta'];
            $venta->fecha               = $request['dateFactura'];
            $venta->fechaVencimiento    = $request['dateFactura'];
            $venta->descuento           = $request['descuentoVenta'];
            $venta->igv                 = 18;
            $venta->impuestos           = $request['igvVenta'];
            $venta->subtotal            = $request['subTotalVenta'];
            $venta->total               = $request['totalVenta'];
            $venta->estadoEmail         = false;
            $venta->estadoSunat         = true;
            $venta->observaciones       = $request['observacionVenta'];

            if($venta->save()) {
                // Venta
                $invoice = (new Invoice())
                ->setUblVersion('2.1')
                ->setTipoOperacion('0101') // Catalog. 51
                ->setTipoDoc($tiposcomprobante->codigo)
                ->setSerie($request['serieVenta'])
                ->setCorrelativo( $request['facturaVenta'])
                ->setFechaEmision(new \DateTime(date("d-m-Y H:i:s", strtotime($request['dateFactura']))))
                ->setTipoMoneda($tipoMoneda->abreviatura)
                ->setClient($client)
                ->setMtoOperGravadas($venta->subtotal) //100
                ->setMtoIGV($venta->impuestos) //18
                ->setTotalImpuestos($venta->impuestos) //18
                ->setValorVenta( $venta->subtotal) //100
                ->setMtoImpVenta($venta->total) //118
                ->setCompany($company);
                $items = [];

                $tiposcomprobante               = tiposComprobantes::find($request['tipoComprobante']);
                $tiposcomprobante->correlativo  = $request['facturaVenta']+1;
                $tiposcomprobante->update();

                for ($x = 0; $x < count($request['cantidad']); $x++) {
                    $ventaDetalles = new detallesVentas;
                    $ventaDetalles->venta_id    = $venta->id;
                    $ventaDetalles->producto_id = $request['nombreProducto'][$x];
                    $ventaDetalles->cantidad    = $request['cantidad'][$x];
                    $ventaDetalles->igv         = $request['total'][$x] - $request['subtotal'][$x];
                    $ventaDetalles->descuento   = $request['descuento'][$x];
                    $ventaDetalles->subtotal    = $request['subtotal'][$x];
                    $ventaDetalles->total       = $request['total'][$x];
                    $ventaDetalles->save();

                    $producto           = Productos::find($request['nombreProducto'][$x]);
                    $producto->cantidad = $producto->cantidad - $request['cantidad'][$x];
                    $producto->vendido  = $producto->vendido + $request['cantidad'][$x];

                    if($producto->update()){

                        $producto = productos::find($request['nombreProducto'][$x]);

                        $precioFinalProducto  = $producto->precio - $request['descuento'][$x];
                        $productoPrecioSinIgv = $precioFinalProducto/1.18;
                        $productoPrecioCantidadSinIgv = $productoPrecioSinIgv * $request['cantidad'][$x];
                        $productoImpuesto             = $request['total'][$x] - $request['subtotal'][$x];

                        $items[$x] = (new SaleDetail())
                            ->setCodProducto($producto->codigo)
                            ->setUnidad('NIU')
                            ->setCantidad($request['cantidad'][$x])//2
                            ->setDescripcion($producto->nombre)
                            ->setMtoBaseIgv($productoPrecioCantidadSinIgv)  //100
                            ->setPorcentajeIgv(18.00) // 18%
                            ->setIgv(sprintf("%.2f", $productoImpuesto))    //18
                            ->setTipAfeIgv('10')
                            ->setTotalImpuestos(sprintf("%.2f", $productoImpuesto))//18
                            ->setMtoValorVenta($productoPrecioCantidadSinIgv)//100
                            ->setMtoValorUnitario($productoPrecioSinIgv)//50
                            ->setMtoPrecioUnitario($precioFinalProducto);//59


                        $control = new control;
                        $control->user_id       = $request->header('usuid');
                        $control->metodo        = "Actualizar";
                        $control->tabla         = "Productos";
                        $control->campos        = "cantidad";
                        $control->datos         = $request['cantidad'][$x];
                        $control->descripcion   = "Actualizar la cantidad de productos despues de realizar una venta";
                        $control->save();
                    }
                }
            }

            $legend = (new Legend())
                            ->setCode('1000')
                            ->setValue(NumerosEnLetras::convertir($venta->total).'/100 SOLES');

            $invoice->setDetails($items)
                    ->setLegends([$legend]);

            $result = $see->send($invoice);

            // Guardar XML
            file_put_contents(public_path('\sunat\xml\venta-'.$venta->id.'-'.$invoice->getName().'.xml'),
                                $see->getFactory()->getLastXml());
            if (!$result->isSuccess()) {
            var_dump($result->getError());
            exit();
            }

            // Guardar CDR
            file_put_contents(public_path('\sunat\zip\venta-'.$venta->id.'-R-'.$invoice->getName().'.zip'), $result->getCdrZip());

            $venta      = ventas::find($venta->id);
            $venta->xml = "\sunat\xml\venta-".$venta->id."-".$invoice->getName().".xml";
            $venta->cdr = '\sunat\zip\venta-'.$venta->id.'-R-'.$invoice->getName().'.zip';
            $venta->update();

            $codigoQr = QrCode::format('png')
                                ->size(250)
                                ->generate(
                                    "20000000001|".$tiposcomprobante->codigo."|".$request['serieVenta']."|".$request['facturaVenta']."|".$venta->impuestos."|".$venta->total."|".$request['dateFactura']."|".$tipoDocumento->codigo."|".$request['numeroDocumento']."|", public_path('img/qr.png')
                                );

            // IMPRIMIR TICKET
            $nombre_impresora = "POS";

            $connector = new WindowsPrintConnector($nombre_impresora);
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);


            try{
                $logo = EscposImage::load(public_path('img/logo.png'), false);
                $printer->bitImage($logo);
            }catch(Exception $e){/*No hacemos nada si hay error*/}


            $printer->text("\n"."LA EMPRESA (20000000001)"."\n");
            $printer->text("Dirección: Dean Cerro 412 A" . "\n");
            $printer->text("Tel: 054 77 34 22" . "\n");
            $printer->text("\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("FACTURA ELECTRONICA"."\n");
            $printer->text("SERIE: ".$request['serieVenta']."-".$request['facturaVenta']."\n");
            #La fecha tambi�n
            $printer->text(date("Y-m-d H:i:s") . "\n");
            $printer->text("\n");
            $printer->text("Señor(es): ".$request['razonSocial']."\n");
            $printer->text("Dirección: ".$request['direccion']."\n");
            $printer->text("RUC: ".$request['numeroDocumento']."\n");
            $printer->text("-----------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("CANT  DESCRIPCION    P.U   IMP.\n");
            $printer->text("-----------------------------"."\n");

                $printer->setJustification(Printer::JUSTIFY_LEFT);
                for ($x = 0; $x < count($request['cantidad']); $x++) {

                    $producto = productos::where('id', $request['nombreProducto'][$x])
                                        ->first();

                    $printer->text($producto['nombre'].": \n");
                    $printer->text( $request['cantidad'][$x]."  unidad    ".$producto['precio']." ".$request['total'][$x]."   \n");


                }


            $printer->text("-----------------------------"."\n");
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("SUBTOTAL: ".$request['subTotalVenta']."\n");
            $printer->text("IVA: ".$request['igvVenta']."\n");
            $printer->text("TOTAL: ".$request['totalVenta']."\n");

            $printer->text("\n");

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Puede consultar en EMPRESAweb.com (https://EMPRESAweb.com/consultas)"."\n");
            $printer->text("\n");
            $printer->text("\n");
            $imgQr = EscposImage::load(public_path('img/qr.png'), false);
            $printer->bitImage($imgQr);
            $printer->text("Muchas gracias por su compra\n");

            $printer->feed(3);
            $printer->cut();
            $printer->pulse();
            $printer->close();



            DB::commit();

            $rpta = array(
                'response'      =>  true,
                'respuestaSunat' => $result->getCdrResponse()->getDescription(),
                'setValue' => NumerosEnLetras::convertir($venta->total).'/100 SOLES',
                'ventaTotal' => $venta->total,
                'invoice' => $invoice
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }


    public function guardarEmitirFactura(Request $request)
    {
        DB::beginTransaction();
        try {
            $correlativo = ventas::where('numero', $request['facturaVenta'])
                                ->where('tipoComprobante_id', $request['tipoComprobante'])
                                ->first();

            if($correlativo){

                $rpta = array(
                    'response'      =>  false,
                );

                return json_encode($rpta);
            }

            $cliente = clientes::where('documento', $request['numeroDocumento'])
                            ->first();

            if($cliente){
                $idCliente = $cliente->id;
            }else{
                $cliente = new clientes;
                $cliente->tipoDocumento_id = $request['tipoDocumento'];
                $cliente->documento = $request['numeroDocumento'];
                $cliente->nombre = $request['razonSocial'];
                $cliente->save();

                $idCliente = $cliente->id;
            }
            $tipoDocumento    = tiposDocumentos::find($request['tipoDocumento']);
            $venta = new ventas;
            $venta->tipoComprobante_id  = $request['tipoComprobante'];
            $venta->cliente_id          = $idCliente;
            $venta->user_id             = $request->header('usuid');
            $venta->tipoMoneda_id       = $request['tipoMoneda'];
            $venta->numero              = 0;
            $venta->fecha               = $request['dateFactura'];
            $venta->fechaVencimiento    = $request['dateFactura'];
            $venta->descuento           = $request['descuentoVenta'];
            $venta->igv                 = 18;
            $venta->impuestos           = $request['igvVenta'];
            $venta->subtotal            = $request['subTotalVenta'];
            $venta->total               = $request['totalVenta'];
            $venta->estadoEmail         = false;
            $venta->estadoSunat         = false;
            $venta->observaciones       = $request['observacionVenta'];

            if($venta->save()) {

                for ($x = 0; $x < count($request['cantidad']); $x++) {
                    $ventaDetalles = new detallesVentas;
                    $ventaDetalles->venta_id    = $venta->id;
                    $ventaDetalles->producto_id = $request['nombreProducto'][$x];
                    $ventaDetalles->cantidad    = $request['cantidad'][$x];
                    $ventaDetalles->igv         = $request['total'][$x] - $request['subtotal'][$x];
                    $ventaDetalles->descuento   = $request['descuento'][$x];
                    $ventaDetalles->subtotal    = $request['subtotal'][$x];
                    $ventaDetalles->total       = $request['total'][$x];
                    $ventaDetalles->save();

                }
            }
            DB::commit();

            $rpta = array(
                'response'      =>  true,
                'setValue' => NumerosEnLetras::convertir($venta->total).'/100 SOLES',
                'ventaTotal' => $venta->total,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }


    public function emitirBoleta(Request $request)
    {
        date_default_timezone_set("America/Lima");
        // ENVIAR A LA SUNAT
        $rucEmpresa     = "20000000001";
        $usuarioEmpresa = "MODDATOS";
        $passEmpresa    = "moddatos";

        $see = new See();
        $see->setService(SunatEndpoints::FE_BETA);
        $see->setCertificate(file_get_contents(public_path('\sunat\certificadosfree\certificadofree.pem')));
        $see->setCredentials('20000000001MODDATOS'/*ruc+usuario*/, 'moddatos');
        // ---------- FACTURACION -------------
        $tipoDocumento    = tiposDocumentos::find($request['tipoDocumento']);
        $tiposcomprobante = tiposComprobantes::find($request['tipoComprobante']);
        $tipoMoneda       = tiposMonedas::find($request['tipoMoneda']);

        // Cliente
        $client = new Client();
        $client->setTipoDoc($tipoDocumento->codigo) //6 es RUC
            ->setNumDoc($request['numeroDocumento'])
            ->setRznSocial($request['nombreCliente']);

        // Emisor
        $address = new Address();
        $address->setUbigueo('040101')
            ->setDepartamento('AREQUIPA')
            ->setProvincia('AREQUIPA')
            ->setDistrito('AREQUIPA')
            ->setUrbanizacion('NONE')
            ->setDireccion('CAL. DEAN Cerro 410, 412, 4 NRO. --');

        $company = new Company();
        $company->setRuc('20000000001')
                ->setRazonSocial('LA  E.I.R.L')
                ->setNombreComercial('EMPRESA')
                ->setAddress($address);

        DB::beginTransaction();
        try {

            $cliente = clientes::where('documento', $request['numeroDocumento'])
                                ->first();

            if($cliente){
                $idCliente = $cliente->id;
            }else{
                $cliente = new clientes;
                $cliente->tipoDocumento_id = $request['tipoDocumento'];
                $cliente->documento = $request['numeroDocumento'];
                $cliente->nombre = $request['nombreCliente'];
                $cliente->save();

                $idCliente = $cliente->id;
            }

            $venta = new ventas;
            $venta->tipoComprobante_id  = $request['tipoComprobante'];
            $venta->cliente_id          = $idCliente;
            $venta->user_id             = $request->header('usuid');
            $venta->tipoMoneda_id       = $request['tipoMoneda'];
            $venta->numero              = $request['facturaVenta'];
            $venta->fecha               = $request['dateFactura'];
            $venta->fechaVencimiento    =  $request['dateFactura'];
            $venta->descuento           = $request['descuentoVenta'];
            $venta->igv                 = 18;
            $venta->impuestos           = $request['igvVenta'];
            $venta->subtotal            = $request['subTotalVenta'];
            $venta->total               = $request['totalVenta'];
            $venta->estadoEmail         = false;
            $venta->estadoSunat         = true;
            $venta->observaciones       = $request['observacionVenta'];

            if($venta->save()) {
                // Venta
                $fechaActual = date('Y-m-d');
                $invoice = (new Invoice())
                ->setUblVersion('2.1')
                ->setTipoOperacion('0101') // Catalog. 51
                ->setTipoDoc($tiposcomprobante->codigo)
                ->setSerie($request['serieVenta'])
                ->setCorrelativo( $request['facturaVenta'])
                ->setFechaEmision(new \DateTime(date("d-m-Y H:i:s", strtotime($request['dateFactura']))))
                ->setTipoMoneda($tipoMoneda->abreviatura)
                ->setClient($client)
                ->setMtoOperGravadas($venta->subtotal) //100
                ->setMtoIGV($venta->impuestos) //18
                ->setTotalImpuestos($venta->impuestos) //18
                ->setValorVenta( $venta->subtotal ) //100
                ->setMtoImpVenta( $venta->total ) //118
                ->setCompany($company);
                $items = [];

                $tiposcomprobante               = tiposComprobantes::find($request['tipoComprobante']);
                $tiposcomprobante->correlativo  = $request['facturaVenta']+1;
                $tiposcomprobante->update();

                for ($x = 0; $x < count($request['cantidad']); $x++) {
                    $ventaDetalles = new detallesVentas;
                    $ventaDetalles->venta_id    = $venta->id;
                    $ventaDetalles->producto_id = $request['nombreProducto'][$x];
                    $ventaDetalles->cantidad    = $request['cantidad'][$x];
                    $ventaDetalles->igv         = $request['total'][$x] - $request['subtotal'][$x];
                    $ventaDetalles->descuento   = $request['descuento'][$x];
                    $ventaDetalles->subtotal    = $request['subtotal'][$x];
                    $ventaDetalles->total       = $request['total'][$x];
                    $ventaDetalles->save();

                    $producto = Productos::find($request['nombreProducto'][$x]);
                    $producto->cantidad = $producto->cantidad - $request['cantidad'][$x];
                    $producto->vendido  = $producto->vendido + $request['cantidad'][$x];

                    if($producto->update()){
                        $producto = productos::find($request['nombreProducto'][$x]);

                        $precioFinalProducto  = $producto->precio - $request['descuento'][$x];
                        $productoPrecioSinIgv = $precioFinalProducto/1.18;
                        $productoPrecioCantidadSinIgv   = $productoPrecioSinIgv* $request['cantidad'][$x];
                        $productoImpuesto               = $request['total'][$x] - $request['subtotal'][$x];
                        $items[$x] = (new SaleDetail())
                            ->setCodProducto($producto->codigo)
                            ->setUnidad('NIU')
                            ->setCantidad($request['cantidad'][$x])
                            ->setDescripcion($producto->nombre)
                            ->setMtoBaseIgv($productoPrecioCantidadSinIgv)
                            ->setPorcentajeIgv(18.00) // 18%
                            ->setIgv(sprintf("%.2f", $productoImpuesto))
                            ->setTipAfeIgv('10')
                            ->setTotalImpuestos(sprintf("%.2f", $productoImpuesto))
                            ->setMtoValorVenta($productoPrecioCantidadSinIgv)
                            ->setMtoValorUnitario($productoPrecioSinIgv)
                            ->setMtoPrecioUnitario($precioFinalProducto);

                        $control = new control;
                        $control->user_id = $request->header('usuid');
                        $control->metodo = "actualizar";
                        $control->tabla = "Productos";
                        $control->campos = "cantidad";
                        $control->datos = $request['cantidad'][$x];
                        $control->descripcion = "Actualizar la cantidad de productos despues de realizar una venta con  boleta";
                        $control->save();
                    }
                }
            }

            $legend = (new Legend())
            ->setCode('1000')
            ->setValue(NumerosEnLetras::convertir($venta->total).'/100 SOLES');

            $invoice->setDetails($items)
                    ->setLegends([$legend]);

            $result = $see->send($invoice);

            // Guardar XML
            file_put_contents(public_path('\sunat\xml\venta-'.$venta->id.'-'.$invoice->getName().'.xml'),
                                $see->getFactory()->getLastXml());
            if (!$result->isSuccess()) {
                var_dump($result->getError());
                exit();
            }


            // Guardar CDR
            file_put_contents(
                public_path(
                    '\sunat\zip\venta-'.$venta->id.'-R-'.$invoice->getName().'.zip'
                ),
                $result->getCdrZip()
            );

            $venta      = ventas::find($venta->id);
            $venta->xml = '\sunat\xmp\venta-'.$venta->id.'-'.$invoice->getName().'.xml';
            $venta->cdr = '\sunat\zip\venta-'.$venta->id.'-R-'.$invoice->getName().'.zip';
            $venta->update();

            $codigoQr = QrCode::format('png')->size(250)->generate($rucEmpresa."|".$tiposcomprobante->codigo."|".$request['serieVenta']."|".$request['facturaVenta']."|".$venta->impuestos."|".$venta->total."|".$request['dateFactura']."|".$tipoDocumento->codigo."|".$request['numeroDocumento']."|", public_path('img/qr.png'));

            // IMPRIMIR TICKET
            $nombre_impresora = "POS";

            $connector = new WindowsPrintConnector($nombre_impresora);
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);


            try{
                $logo = EscposImage::load(public_path('img/logo.png'), false);
                $printer->bitImage($logo);
            }catch(Exception $e){/*No hacemos nada si hay error*/}


            $printer->text("\n"."LA EMPRESA (20000000001)"."\n");
            $printer->text("Direccion: Dean Cerro 412 A" . "\n");
            $printer->text("Tel: 054 77 34 22" . "\n");
            $printer->text("\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("BOLETA ELECTRONICA"."\n");
            $printer->text("SERIE: ".$request['serieVenta']."-".$request['facturaVenta']."\n");
            #La fecha tambi�n

            $printer->text(date("Y-m-d H:i:s") . "\n");
            $printer->text("-----------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("CANT  DESCRIPCION    P.U   IMP.\n");
            $printer->text("-----------------------------"."\n");

                $printer->setJustification(Printer::JUSTIFY_LEFT);
                for ($x = 0; $x < count($request['cantidad']); $x++) {

                    $producto = productos::where('id', $request['nombreProducto'][$x])
                                        ->first();

                    $printer->text($producto['nombre'].": \n");
                    $printer->text( $request['cantidad'][$x]."  unidad    ".$producto['precio']." ".$request['total'][$x]."   \n");


                }


            $printer->text("-----------------------------"."\n");
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("SUBTOTAL: ".$request['subTotalVenta']."\n");
            $printer->text("IVA: ".$request['igvVenta']."\n");
            $printer->text("TOTAL: ".$request['totalVenta']."\n");
            $printer->text("\n");

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Puede consultar en EMPRESAweb.com (https://EMPRESAweb.com/consultas)"."\n");
            $printer->text("\n");
            $printer->text("\n");
            $imgQr = EscposImage::load(public_path('img/qr.png'), false);
            $printer->bitImage($imgQr);
            $printer->text("Muchas gracias por su compra\n");

            $printer->feed(3);
            $printer->cut();
            $printer->pulse();
            $printer->close();

            DB::commit();

            $rpta = array(
                'response'      =>  true,
                'respuestaSunat' => $result->getCdrResponse()->getDescription(),
                'setValue' => NumerosEnLetras::convertir($venta->total).'/100 SOLES',
                'ventaTotal' => $venta->total,
                'invoice' => $invoice
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }


    public function guardarEmitirBoleta(Request $request)
    {
        DB::beginTransaction();
        try {

            $cliente = clientes::where('documento', $request['numeroDocumento'])
                            ->first();

            if($cliente){
                $idCliente = $cliente->id;
            }else{
                $cliente = new clientes;
                $cliente->tipoDocumento_id = $request['tipoDocumento'];
                $cliente->documento = $request['numeroDocumento'];
                $cliente->nombre = $request['nombreCliente'];
                $cliente->save();

                $idCliente = $cliente->id;
            }
            $tipoDocumento    = tiposDocumentos::find($request['tipoDocumento']);
            $venta = new ventas;
            $venta->tipoComprobante_id  = $request['tipoComprobante'];
            $venta->cliente_id          = $idCliente;
            $venta->user_id             = $request->header('usuid');
            $venta->tipoMoneda_id       = $request['tipoMoneda'];
            $venta->numero              = 0;
            $venta->fecha               = $request['dateFactura'];
            $venta->fechaVencimiento    = $request['dateFactura'];
            $venta->descuento           = $request['descuentoVenta'];
            $venta->igv                 = 18;
            $venta->impuestos           = $request['igvVenta'];
            $venta->subtotal            = $request['subTotalVenta'];
            $venta->total               = $request['totalVenta'];
            $venta->estadoEmail         = false;
            $venta->estadoSunat         = false;
            $venta->observaciones       = $request['observacionVenta'];

            if($venta->save()) {


                for ($x = 0; $x < count($request['cantidad']); $x++) {
                    $ventaDetalles              = new detallesVentas;
                    $ventaDetalles->venta_id    = $venta->id;
                    $ventaDetalles->producto_id = $request['nombreProducto'][$x];
                    $ventaDetalles->cantidad    = $request['cantidad'][$x];
                    $ventaDetalles->igv         = $request['total'][$x] - $request['subtotal'][$x];
                    $ventaDetalles->descuento   = $request['descuento'][$x];
                    $ventaDetalles->subtotal    = $request['subtotal'][$x];
                    $ventaDetalles->total       = $request['total'][$x];
                    $ventaDetalles->save();

                }
            }

            DB::commit();

            $rpta = array(
                'response'      =>  true,
                'setValue' => NumerosEnLetras::convertir($venta->total).'/100 SOLES',
                'ventaTotal' => $venta->total,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }

    public function guardarVentaInterna(Request $request)
    {
        date_default_timezone_set("America/Lima");
        $numeroDocumentoCliente     = $request['numeroDocumento'];
        $tipoDocumentoCliente       = tiposDocumentos::where('codigo', $request['tipoDocumento'])->first();
        $nombreCliente              = $request['nombreCliente'];
        $idUsuario = $request->header('usuid');
        if($idUsuario == 1 || $idUsuario == 2){
            // $idSucursal                  = env('sucursalId');
            $idSucursal = 1;
        }else{
            $idSucursal = 2;
        }


        DB::beginTransaction();
        try {

            $cajaVenta    = cajasVentas::where('sucursal_id', $idSucursal)
                                        ->where('cierre', null)
                                        ->first();

            // QUITAR CUANDO FUNCIONE LA CAJA DE VENTA
            if($cajaVenta){
                $tipoMoneda = tiposmonedas::where('id', $request['tipoMoneda'])
                                                ->where('codigo', 71)
                                                ->first();

                $idCajaVenta = $cajaVenta->id;
                if(!$tipoMoneda){
                    $cajaVenta              = cajasVentas::find($idCajaVenta);
                    $cajaVenta->totalCierre = $cajaVenta->totalCierre + $request['totalVenta'];
                    $cajaVenta->update();
                }
            }else{
                $idCajaVenta = null;
            }

            $cliente = clientes::where('documento', $numeroDocumentoCliente)
                                ->first();

            if($cliente){
                $idCliente = $cliente->id;
            }else{
                $cliente = new clientes;
                $cliente->tipoDocumento_id  = $tipoDocumentoCliente->id;
                $cliente->documento         = $numeroDocumentoCliente;
                $cliente->nombre            = $nombreCliente;
                $cliente->save();

                $idCliente = $cliente->id;
            }

            $venta = new ventas;
            $venta->tipoComprobante_id  = $request['tipoComprobante'];
            $venta->sucursal_id         = $request['sucursalId'];
            $venta->cliente_id          = $idCliente;
            $venta->user_id             = $request->header('usuid');
            $venta->tipoMoneda_id       = $request['tipoMoneda'];
            $venta->cajaVenta_id        = $idCajaVenta;
            $venta->numero              = $request['numeroVenta'];
            $venta->fecha               = date('Y-m-d');
            $venta->fechaVencimiento    = date('Y-m-d');
            $venta->descuento           = $request['descuentoVenta'];
            $venta->igv                 = 18;
            $venta->impuestos           = $request['igvVenta'];
            $venta->subtotal            = $request['subTotalVenta'];
            $venta->total               = $request['totalVenta'];
            $venta->estadoEmail         = false;
            $venta->estadoSunat         = false;
            $venta->observaciones       = $request['observacionVenta'];
            $venta->created_at          = date('Y-m-d H:i:s');

            $detallesVenta              = $request['detallesVenta'];

            if($venta->save()) {
                $tiposcomprobante               = tiposComprobantes::find($request['tipoComprobante']);
                $tiposcomprobante->correlativo  = $tiposcomprobante->correlativo+1;
                $tiposcomprobante->update();

                for ($x = 0; $x < count($detallesVenta); $x++) {
                    if($detallesVenta[$x]['idProducto'] != 0){
                        $ventaDetalles              = new detallesVentas;
                        $ventaDetalles->venta_id    = $venta->id;
                        $ventaDetalles->producto_id = $detallesVenta[$x]['idProducto'];
                        $ventaDetalles->cantidad    = $detallesVenta[$x]['cantidadProducto'];
                        $ventaDetalles->igv         = $detallesVenta[$x]['totalProducto'] - $detallesVenta[$x]['subTotalProducto'];
                        $ventaDetalles->descuento   = $detallesVenta[$x]['totalDescuento'];
                        $ventaDetalles->subtotal    = $detallesVenta[$x]['subTotalProducto'];
                        $ventaDetalles->total       = $detallesVenta[$x]['totalProducto'];
                        if($ventaDetalles->save()){
                            $producto           = Productos::find($detallesVenta[$x]['idProducto']);
                            $producto->cantidad = $producto->cantidad - $detallesVenta[$x]['cantidadProducto'];
                            $producto->vendido  = $producto->vendido + $detallesVenta[$x]['cantidadProducto'];

                            if($producto->update()){
                                
                                $almacen               = almacenes::where('producto_id', $producto->id)
                                                                    ->where('sucursal_id', $idSucursal)
                                                                    ->first();
                                if($almacen){
                                    $almacen->stock        = $almacen->stock   - $detallesVenta[$x]['cantidadProducto'];
                                    $almacen->vendido      = $almacen->vendido + $detallesVenta[$x]['cantidadProducto'];
                                    $almacen->update();

                                }else{
                                    $almacen = new almacenes;
                                    $almacen->sucursal_id   = $idSucursal;
                                    $almacen->producto_id   = $producto->id;
                                    $almacen->stock         = 0-$detallesVenta[$x]['cantidadProducto'];
                                    $almacen->vendido       = $detallesVenta[$x]['cantidadProducto'];
                                    $almacen->total         = 0;
                                    $almacen->save();
                                }
                            }
                        }
                    }
                }
                
            }

            DB::commit();

            $rpta = array(
                'response'      => true,
                'idVenta'       => $venta->id,
                'setValue'      => NumerosEnLetras::convertir($venta->total).'/100 SOLES',
                'ventaTotal'    => $venta->total,
                'detalleVenta'  => $detallesVenta,
            );
            return json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            return json_encode($e->getMessage());
        }
    }

    public function comprobanteEmitir(Request $request)
    {
        DB::beginTransaction();
        try {
        $ventas = ventas::join('clientes as c', 'ventas.cliente_id', '=', 'c.id')
                        ->join('tiposDocumentos as td', 'c.tipoDocumento_id', '=', 'td.id')
                        ->join('tiposComprobantes as tc', 'ventas.tipoComprobante_id', '=', 'tc.id')
                        ->join('tiposMonedas as tm', 'ventas.tipoMoneda_id', '=', 'tm.id')
                        ->where('ventas.id', '=', $request['id'])
                        ->first([
                            'td.codigo          as codigoTiposdocumento',
                            'c.documento        as documentoClientes',
                            'c.nombre           as nombreClientes',
                            'tc.codigo          as codigoTiposcomprobante',
                            'tc.serie           as serieTiposcomprobante',
                            'tc.correlativo     as correlativoTiposcomprobante',
                            'ventas.numero      as numeorVentas',
                            'ventas.fecha       as fechaVentas',
                            'tm.abreviatura     as abreviaturaTiposmoneda',
                            'ventas.subtotal    as subtotalVentas',
                            'ventas.impuestos   as impuestosVentas',
                            'ventas.total       as totalVentas',
                            'ventas.id          as idVentas'
                        ]);


        $see = new See();
        $see->setService(SunatEndpoints::FE_BETA);
        $see->setCertificate(file_get_contents(public_path('\sunat\certificadosfree\certificadofree.pem')));
        $see->setCredentials('20000000001MODDATOS'/*ruc+usuario*/, 'moddatos');

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
            ->setDireccion('CAL. DEAN Cerro 410, 412, 4 NRO. --');

        $company = new Company();
        $company->setRuc('20000000001')
                ->setRazonSocial('LA EMPRESA DISTRIBUCIONES IMPORTACIONES E.I.R.L')
                ->setNombreComercial('EMPRESA')
                ->setAddress($address);

        // Venta
        $fechaActual = date('Y-m-d');
        $invoice = (new Invoice())
        ->setUblVersion('2.1')
        ->setTipoOperacion('0101') // Catalog. 51
        ->setTipoDoc($ventas->codigoTiposcomprobante)
        ->setSerie($ventas->serieTiposcomprobante)
        ->setCorrelativo($ventas->correlativoTiposcomprobante)
        ->setFechaEmision(new \DateTime(date("d-m-Y H:i:s", strtotime($ventas->fechaVentas))))
        ->setTipoMoneda($ventas->abreviaturaTiposmoneda)
        ->setClient($client)
        ->setMtoOperGravadas($ventas->subtotalVentas) //100
        ->setMtoIGV($ventas->impuestosVentas) //18
        ->setTotalImpuestos($ventas->impuestosVentas) //18
        ->setValorVenta( $ventas->subtotalVentas) //100
        ->setMtoImpVenta($ventas->totalVentas) //118
        ->setCompany($company);

        $tiposcomprobante = tiposComprobantes::where('codigo', $ventas->codigoTiposcomprobante)->first();
        $ventaSelecionada = ventas::find($ventas->idVentas);
        $ventaSelecionada->numero = $tiposcomprobante->correlativo;
        $ventaSelecionada->update();

        $tiposcomprobante->correlativo = $tiposcomprobante->correlativo+1;
        $tiposcomprobante->update();



        $items = [];

        $ventaDetalles = detallesVentas::where('venta_id', $ventas->idVentas)
                                        ->get();
        $x = 0;
        foreach($ventaDetalles as $ventaDetalle){

            $producto = productos::where('id', $ventaDetalle->producto_id )
                                    ->first();

            $producto->cantidad = $producto->cantidad - $ventaDetalle->cantidad;
            $producto->vendido = $producto->vendido + $ventaDetalle->cantidad;
            if($producto->update()){
                $control = new control;
                $control->user_id = $request->header('usuid');
                $control->metodo = "actualizar";
                $control->tabla = "Productos";
                $control->campos = "cantidad";
                $control->datos = $ventaDetalle->cantidad;
                $control->descripcion = "Actualizar la cantidad de productos despues de realizar una venta";
                $control->save();
            }

            $precioFinalProducto    = $producto->precio - $ventaDetalle->descuento;
            $productoImpuesto       = $ventaDetalle->total - $ventaDetalle->subtotal;
            $productoPrecioSinIgv   = $precioFinalProducto/1.18;
            $productoPrecioCantidadSinIgv = $productoPrecioSinIgv * $ventaDetalle->cantidad;
            $items[$x] = (new SaleDetail())
                ->setCodProducto($producto->codigo)
                ->setUnidad('NIU')
                ->setCantidad($ventaDetalle->cantidad)
                ->setDescripcion($producto->nombre)
                ->setMtoBaseIgv($productoPrecioCantidadSinIgv)
                ->setPorcentajeIgv(18.00) // 18%
                ->setIgv($productoImpuesto)
                ->setTipAfeIgv('10')
                ->setTotalImpuestos($productoImpuesto)
                ->setMtoValorVenta($productoPrecioCantidadSinIgv)
                ->setMtoValorUnitario($productoPrecioSinIgv)
                ->setMtoPrecioUnitario($producto->precio);

            $x = $x+1;
        }

        $legend = (new Legend())
        ->setCode('1000')
        ->setValue(NumerosEnLetras::convertir($ventas->totalVentas).'/100 SOLES');

        $invoice->setDetails($items)
                ->setLegends([$legend]);

        $result = $see->send($invoice);

        // Guardar XML
        file_put_contents(public_path('\sunat\xml\venta-'.$ventas->idVentas.'-'.$invoice->getName().'.xml'),
                            $see->getFactory()->getLastXml());
        if (!$result->isSuccess()) {
            var_dump($result->getError());
            exit();
        }else{
            $venta = ventas::find($ventas->idVentas);
            $venta->estadoSunat = 1;
            $venta->update();
        }


        // Guardar CDR
        file_put_contents(public_path('\sunat\zip\venta-'.$ventas->id.'-R-'.$invoice->getName().'.zip'), $result->getCdrZip());

        $venta      = ventas::find($venta->id);
        $venta->xml = "\sunat\xml\venta-".$venta->id."-".$invoice->getName().".xml";
        $venta->cdr = '\sunat\zip\venta-'.$venta->id.'-R-'.$invoice->getName().'.zip';
        $venta->update();

        DB::commit();

            $rpta = array(
                'response'      =>  true,
                'setValue' => NumerosEnLetras::convertir($ventas->total).'/100 SOLES',
                'ventaTotal' => $ventas->total,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }

    public function productoTemporalCrear(Request $request)
    {
        DB::beginTransaction();
        try {
            if($request['codigoProductoNuevo'] == null){
                $codigo = "TEMPORAL";
            }else{
                $codigo = $request['codigoProductoNuevo'];
            }

            $idUsuario = $request->header('usuid');
            if($idUsuario == 1 || $idUsuario == 2){
                // $idSucursal                  = env('sucursalId');
                $idSucursal = 1;
            }else{
                $idSucursal = 2;
            }

            $productos                          = new Productos;
            $productos->codigo                  = $codigo;
            $productos->marca_id                = 1000;
            $productos->tipo_id                 = 1000;
            $productos->nombre                  = $request['nombreProductoNuevo'];
            $productos->cantidad                = 0;
            $productos->precio                  = $request['precioVentaProducto'];
            $productos->total                   = 0;
            $productos->vendido                 = 0;
            $productos->tipoCajaProducto_id     = 1;

            if($productos->save()) {

                $almacen = new almacenes;
                $almacen->sucursal_id   = $idSucursal;
                $almacen->producto_id   = $productos->id;
                $almacen->stock         = 0;
                $almacen->vendido       = 0;
                $almacen->total         = 0;
                $almacen->save();

                $response = true;
            }else{
                $response = false;
            }

            DB::commit();

            $rpta = array(
                'response'          =>  $response,
                'producto'          =>  $productos,
                'idProducto'        =>  $productos->id,
                'nombreProducto'    =>  $productos->nombre,
                'codigoProducto'    =>  $productos->codigo,
                'precioProducto'    =>  $productos->precio,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }

    public function verDetalleVenta($idVenta)
    {

        $venta   = ventas::find($idVenta);
        $usuario = User::find($venta->user_id);
        $cliente = clientes::find($venta->cliente_id);

        $detallesVenta = detallesVentas::join('productos as p', 'p.id', '=', 'detallesVentas.producto_id')
                                    ->where('venta_id', $idVenta)
                                    ->get([
                                        'p.nombre                   as nombreProducto',
                                        'p.precio                   as precioProducto',
                                        'detallesVentas.cantidad    as cantidadProducto',
                                        'detallesVentas.igv         as igvProducto',
                                        'detallesVentas.descuento   as descuentoProducto',
                                        'detallesVentas.subtotal    as subtotalProducto',
                                        'detallesVentas.total       as totalProducto'
                                    ]);
                                    
        $data = array(
            'item'  =>  1,
            "cliente" => $cliente,
            "usuario" => $usuario,
            'venta'         => $venta,
            'detallesVenta' => $detallesVenta,
        );

        $pdf = PDF::loadView('ventas.pdf.detalleVentaPdf', $data)->setPaper('a4');
        return $pdf->stream();

    }

    public function descargarXml($idVenta)
    {

        // Guardar CDR
        // file_put_contents(public_path('\sunat\zip\venta-'.$ventas->idVentas.'-R-'.$invoice->getName().'.zip'), $result->getCdrZip());

        $venta = ventas::find($idVenta);
        $file   =   public_path($venta->xml);
        return Response::download($file);

    }

    public function notaCredito(Request $request)
    {
        date_default_timezone_set("America/Lima");
        $idVenta = $request['id'];
        $motivo  = $request['motivo'];
        DB::beginTransaction();
        try {
            $ventas = ventas::join('clientes as c', 'ventas.cliente_id', '=', 'c.id')
                            ->join('tiposDocumentos as td', 'c.tipoDocumento_id', '=', 'td.id')
                            ->join('tiposComprobantes as tc', 'ventas.tipoComprobante_id', '=', 'tc.id')
                            ->join('tiposMonedas as tm', 'ventas.tipoMoneda_id', '=', 'tm.id')
                            ->where('ventas.id', '=', $idVenta)
                            ->first([
                                'td.codigo          as codigoTiposdocumento',
                                'c.documento        as documentoClientes',
                                'c.nombre           as nombreClientes',
                                'tc.codigo          as codigoTiposcomprobante',
                                'tc.serie           as serieTiposcomprobante',
                                'tm.abreviatura     as abreviaturaTiposmoneda',
                                'ventas.numero      as numeroVentas',
                                'ventas.fecha       as fechaVentas',
                                'ventas.subtotal    as subtotalVentas',
                                'ventas.impuestos   as impuestosVentas',
                                'ventas.total       as totalVentas',
                                'ventas.id          as idVentas'
                            ]);


            $see = new See();
            $see->setService(SunatEndpoints::FE_BETA);
            $see->setCertificate(file_get_contents(public_path('\sunat\certificadosfree\certificadofree.pem')));
            $see->setCredentials('20000000001MODDATOS'/*ruc+usuario*/, 'moddatos');

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
                ->setDireccion('CAL. DEAN Cerro 410, 412, 4 NRO. --');

            $company = new Company();
            $company->setRuc('20000000001')
                    ->setRazonSocial('LA EMPRESA DISTRIBUCIONES IMPORTACIONES E.I.R.L')
                    ->setNombreComercial('EMPRESA')
                    ->setAddress($address);

            $note = new Note();
            $note
                ->setUblVersion('2.1')
                ->setTipDocAfectado($ventas->codigoTiposcomprobante)
                ->setNumDocfectado($ventas->serieTiposcomprobante.'-'.$ventas->numeroVentas)
                ->setCodMotivo('07')
                ->setDesMotivo($motivo) //DEVOLUCION POR ITEM
                ->setTipoDoc('07') // NOTA DE CREDITO
                ->setSerie($ventas->serieTiposcomprobante)
                ->setFechaEmision(new \DateTime())
                ->setCorrelativo($ventas->numeroVentas)
                ->setTipoMoneda($ventas->abreviaturaTiposmoneda)
                ->setCompany($company)
                ->setClient($client)
                ->setMtoOperGravadas($ventas->subtotalVentas)
                ->setMtoIGV($ventas->impuestosVentas)
                ->setTotalImpuestos($ventas->impuestosVentas)
                ->setMtoImpVenta($ventas->totalVentas);


            $items = [];

            $ventaDetalles = detallesVentas::where('venta_id', $idVenta)
                                            ->get();
            $x = 0;
            if(sizeof($ventaDetalles) > 0){
                foreach($ventaDetalles as $ventaDetalle){

                    $producto = productos::where('id', $ventaDetalle->producto_id )
                                            ->first();
                    if($producto){
                        $precioFinalProducto    = $producto->precio - $ventaDetalle->descuento;
                        $productoImpuesto       = $ventaDetalle->total - $ventaDetalle->subtotal;
                        $productoPrecioSinIgv   = $precioFinalProducto/1.18;
                        $productoPrecioCantidadSinIgv = $productoPrecioSinIgv * $ventaDetalle->cantidad;
                        $items[$x] = (new SaleDetail())
                            ->setCodProducto($producto->codigo)
                            ->setUnidad('NIU')
                            ->setCantidad($ventaDetalle->cantidad)
                            ->setDescripcion($producto->nombre)
                            ->setMtoBaseIgv($productoPrecioCantidadSinIgv)
                            ->setPorcentajeIgv(18.00) // 18%
                            ->setIgv($productoImpuesto)
                            ->setTipAfeIgv('10')
                            ->setTotalImpuestos($productoImpuesto)
                            ->setMtoValorVenta($productoPrecioCantidadSinIgv)
                            ->setMtoValorUnitario($productoPrecioSinIgv)
                            ->setMtoPrecioUnitario($producto->precio);

                        $productoEditar = productos::find($ventaDetalle->producto_id);
                        $productoEditar->cantidad   = $productoEditar->cantidad + $ventaDetalle->cantidad;
                        $productoEditar->vendido    = $productoEditar->vendido  - $ventaDetalle->cantidad;

                        if($productoEditar->update()){

                        }else{
                            $rpta = array(
                                'response'      =>  false,
                                'mensaje'       => "NO SE PUDO ACTUALIZAR LA CANTIDAD O LO VENDIDO DE LA TABLA PRODUCTOS",
                            );
                            return json_encode($rpta);
                        }


                        $x = $x+1;
                    }else{
                        $rpta = array(
                            'response'      =>  false,
                            'mensaje'       => "NO EXISTE EL PRODUCTO"
                        );
                        return json_encode($rpta);
                    }

                }
            }else{
                $rpta = array(
                    'response'      =>  false,
                    'mensaje'       => "NO EXISTE EL DETALLE DE LA VENTA"
                );
                return json_encode($rpta);
            }

            $legend = (new Legend())
                ->setCode('1000')
                ->setValue(NumerosEnLetras::convertir($ventas->totalVentas).'/100 SOLES');

            $note->setDetails($items)
                ->setLegends([$legend]);

            // Envio a SUNAT.
            $res = $see->send($note);
            file_put_contents(
                public_path(
                    '\sunat\notaCredito\xml\venta-'.$ventas->idVentas.'-'.$note->getName().'.xml'
                ),
                $see->getFactory()->getLastXml()
            );

            if ($res->isSuccess()) {

                $venta = ventas::find($idVenta);
                $venta->estadoSunat = 2;
                if($venta->update()){

                }else{
                    $rpta = array(
                        'response'      =>  false,
                        'mensaje'       => "NO SE REALIZARON LOS CAMBIOS EN VENTAS",
                        'campos'        => $idVenta
                    );
                    return json_encode($rpta);
                }

                $notaCredito = new notascreditos;
                $notaCredito->venta_id      = $idVenta;
                $notaCredito->usuario_id    = $request->header('usuid');
                $notaCredito->motivo        = $motivo;
                if($notaCredito->save()){

                }else{
                    $rpta = array(
                        'response'      =>  false,
                        'mensaje'       => "NO SE REGISTRO LA NOTA DE CREDITO EN LA BASE DE DATOS",
                        'campos'        => $idVenta
                    );
                    return json_encode($rpta);
                }
            }else{
                var_dump($res->getError());
                exit();

            }

            /**@var $res \Greenter\Model\Response\BillResult*/
            $cdr = $res->getCdrResponse();

            file_put_contents(
                public_path(
                    '\sunat\notaCredito\zip\venta-'.$venta->id.'-R-'.$note->getName().'.zip'
                ),
                $res->getCdrZip()
            );
            DB::commit();
            $rpta = array(
                'response'      =>  $res->isSuccess(),
                'setValue'      => NumerosEnLetras::convertir($venta->total).'/100 SOLES',
                'ventaTotal'    => $venta->total,
            );
            return json_encode($rpta);

        }catch (\Exception $e) {
            DB::rollBack();
            return json_encode($e->getMessage());
        }


    }

    public function cancelarVentaInterna(Request $request)
    {
        date_default_timezone_set("America/Lima");
        $idVenta = $request['id'];
        $motivo  = $request['motivo'];
        DB::beginTransaction();
        try {
            $ventas = ventas::where('ventas.id', '=', $idVenta)
                            ->first([
                                'ventas.tipoMoneda_id as idTipoMoneda',
                                'ventas.sucursal_id as idSucursal',
                                'ventas.numero      as numeroVentas',
                                'ventas.fecha       as fechaVentas',
                                'ventas.subtotal    as subtotalVentas',
                                'ventas.impuestos   as impuestosVentas',
                                'ventas.total       as totalVentas',
                                'ventas.id          as idVentas'
                            ]);

            $ventaDetalles = detallesVentas::where('venta_id', $idVenta)
                                            ->get();
            $x = 0;
            if(sizeof($ventaDetalles) > 0){
                foreach($ventaDetalles as $ventaDetalle){

                    $producto = productos::where('id', $ventaDetalle->producto_id )
                                            ->first();
                    if($producto){
                
                        $productoEditar = productos::find($ventaDetalle->producto_id);
                        $productoEditar->cantidad   = $productoEditar->cantidad + $ventaDetalle->cantidad;
                        $productoEditar->vendido    = $productoEditar->vendido  - $ventaDetalle->cantidad;

                        if($productoEditar->update()){

                            $almacenes = almacenes::where('sucursal_id', $ventas->idSucursal)
                                                    ->where('producto_id', $ventaDetalle->producto_id)
                                                    ->first();
                            $almacenes->stock       = $almacenes->stock + $ventaDetalle->cantidad;
                            $almacenes->vendido     = $almacenes->vendido  - $ventaDetalle->cantidad;

                            if($almacenes->update()){

                            }else{
                                $rpta = array(
                                    'response'      =>  false,
                                    'mensaje'       => "NO SE PUDO ACTUALIZAR LA CANTIDAD DE LA TABLA ALMACENES",
                                );
                                return json_encode($rpta);
                            }

                        }else{
                            $rpta = array(
                                'response'      =>  false,
                                'mensaje'       => "NO SE PUDO ACTUALIZAR LA CANTIDAD O LO VENDIDO DE LA TABLA PRODUCTOS",
                            );
                            return json_encode($rpta);
                        }

                        $x = $x+1;
                    }else{
                        $rpta = array(
                            'response'      =>  false,
                            'mensaje'       => "NO EXISTE EL PRODUCTO"
                        );
                        return json_encode($rpta);
                    }
                }
            }else{
                $rpta = array(
                    'response'      =>  false,
                    'mensaje'       => "NO EXISTE EL DETALLE DE LA VENTA"
                );
                return json_encode($rpta);
            }

            $venta = ventas::find($idVenta);
            $venta->estadoSunat = 2;
            if($venta->update()){

            }else{
                $rpta = array(
                    'response'      =>  false,
                    'mensaje'       => "NO SE REALIZARON LOS CAMBIOS EN VENTAS",
                    'campos'        => $idVenta
                );
                return json_encode($rpta);
            }

            $cajaVenta    = cajasVentas::where('sucursal_id', $ventas->idSucursal)
                                        ->where('cierre', null)
                                        ->first();
            // QUITAR CUANDO FUNCIONE LA CAJA DE VENTA
            if($cajaVenta){
                $idCajaVenta = $cajaVenta->id;
                $tipoMoneda = tiposmonedas::where('id', $ventas->idTipoMoneda)
                                            ->where('codigo', 71)
                                            ->first();

                if(!$tipoMoneda){
                    $cajaVenta              = cajasVentas::find($idCajaVenta);
                    $cajaVenta->totalCierre = $cajaVenta->totalCierre - $ventas->totalVentas;
                    $cajaVenta->update();
                }
            }else{
                $idCajaVenta = null;
            }
            $notaCredito = new notascreditos;
            $notaCredito->venta_id      = $idVenta;
            $notaCredito->usuario_id    = $request->header('usuid');
            $notaCredito->motivo        = $motivo;
            $notaCredito->cajaVenta_id  = $idCajaVenta;
            $notaCredito->estado        = 0;
            if($notaCredito->save()){

            }else{
                $rpta = array(
                    'response'      =>  false,
                    'mensaje'       => "NO SE REGISTRO LA NOTA DE CREDITO EN LA BASE DE DATOS",
                    'campos'        => $idVenta
                );
                return json_encode($rpta);
            }

            DB::commit();
            $rpta = array(
                'respuesta'     => true
            );
            return json_encode($rpta);

        }catch (\Exception $e) {
            DB::rollBack();
            return json_encode($e->getMessage());
        }


    }

    public function imprimirVenta(Request $request)
    {
        date_default_timezone_set("America/Lima");
        $rucEmpresa     = "20000000001";
        $ventas = ventas::join('clientes as c', 'ventas.cliente_id', '=', 'c.id')
                        ->join('tiposDocumentos as td', 'c.tipoDocumento_id', '=', 'td.id')
                        ->join('tiposComprobantes as tc', 'ventas.tipoComprobante_id', '=', 'tc.id')
                        ->join('tiposMonedas as tm', 'ventas.tipoMoneda_id', '=', 'tm.id')
                        ->where('ventas.id', '=', $request['id'])
                        ->first([
                            'td.codigo          as codigoTiposdocumento',
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
                            'ventas.id          as idVentas'
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
            $logo = EscposImage::load(public_path('img/logo.png'), false);
            $printer->bitImage($logo);
        }catch(Exception $e){/*No hacemos nada si hay error*/}


        $printer->text("\n"."LA EMPRESA (20000000001)"."\n");
        $printer->text("Dirección: Dean Cerro 412 A" . "\n");
        $printer->text("Tel: 054 77 34 22" . "\n");
        $printer->text("\n");
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text($ventas->nombreTiposcomprobante." ELECTRONICA"."\n");
        $printer->text("SERIE: ".$ventas->serieTiposcomprobante."-".$ventas->numeroVentas."\n");
        #La fecha tambi�n

        $printer->text(date("Y-m-d H:i:s") . "\n");
        $printer->text("\n");
        $printer->text("Señor(es): ".$ventas->nombreClientes."\n");
        $printer->text("Dirección: ".$ventas->direccionClientes."\n");
        $printer->text("RUC: ".$documentoCliente."\n");
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
        $printer->text("IVA: ".$ventas->impuestosVentas."\n");
        $printer->text("TOTAL: ".$ventas->totalVentas."\n");
        $printer->text("\n");

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Puede consultar en EMPRESAweb.com (https://EMPRESAweb.com/consultas)"."\n");
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
