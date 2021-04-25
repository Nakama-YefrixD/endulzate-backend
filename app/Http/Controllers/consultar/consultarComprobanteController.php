<?php

namespace App\Http\Controllers\consultar;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\tiposComprobantes;
use App\ventas;
use App\clientes;
use App\detallesventa;
use App\User;
use PDF; 
use Response;

class consultarComprobanteController extends Controller
{
    public function index()
    {
        date_default_timezone_set("America/Mexico_City");
        $fechaActual = date("d-m-Y");
        $tiposcomprobantes    = tiposComprobantes::all();
        $data = array(
            'tiposComprobantes' => $tiposcomprobantes,
            'fechaActual'       => $fechaActual,
            'captcha'           => "0"

        );

        return view('consultarComprobante.index')->with($data);
    }

    public function consultar(Request $request)
    {
        date_default_timezone_set("America/Mexico_City");
        $fechaActual = date("d-m-Y");
        $tiposcomprobantes    = tiposComprobantes::all();
        $captcha = "0";
        $data = array(
            'tiposComprobantes' => $tiposcomprobantes,
            'fechaActual'       => $fechaActual,
            'captcha'           => $captcha,
            'noexiste'          => 0
        );
        
       
        $validator = Validator::make(request()->all(), [
            recaptchaFieldName() => recaptchaRuleName()
        ]);
        
        if($validator->fails()) {
           $errors = $validator->errors();
           $data['captcha'] = "1";
            
           return view('consultarComprobante.index')->with($data);
        }else{
            $date = date("Y-m-d", strtotime($request['fechaEmision']));

            $venta = ventas::where('tipoComprobante_id', $request['tipodocumento'])
                            ->where('numero', $request['numero'])
                            ->where('fecha', $date)
                            ->first();


            
            if($venta){
                // echo "si hay";
                $usuario = User::find($venta->user_id);
                $cliente = clientes::find($venta->cliente_id);

                $detallesVenta = detallesventa::join('productos as p', 'p.id', '=', 'detallesVentas.producto_id')
                                            ->where('venta_id', $venta->id)
                                            ->get([
                                                'p.nombre                   as nombreProducto',
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

                $file   =   public_path($venta->xml);
                return Response::download($file);
            }else{
                $data['noexiste'] = "1";
            }

            return view('consultarComprobante.index')->with($data);
        }

    }

    public function consultaCaptcha(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            recaptchaFieldName() => recaptchaRuleName()
        ]);

        if($validator->fails()) {
            $errors = $validator->errors();
            $rpta = array(
                'response'      =>  false,
            );    
            
        }else{
            $date = date("Y-m-d", strtotime($request['fechaEmision']));

            $venta = ventas::where('tipoComprobante_id', $request['tipodocumento'])
                            ->where('numero', $request['numero'])
                            ->where('fecha', $date)
                            ->first();
            if($venta){
                $rpta = array(
                    'response'      => true,
                    'venta'         => true,
                    'idVenta'       => $venta->id
                );
            }else{
                $rpta = array(
                    'response'      =>  true,
                    'venta'         => false,
                );
            }

            
        }
        echo json_encode($rpta);
        
        
    }

    public function consultarPdfVenta($idVenta)
    {

        $venta   = ventas::find($idVenta);
        $usuario = User::find($venta->user_id);
        $cliente = clientes::find($venta->cliente_id);

        $detallesVenta = detallesventa::join('productos as p', 'p.id', '=', 'detalles_venta.producto_id')
                                    ->where('venta_id', $idVenta)
                                    ->get([
                                        'p.nombre                   as nombreProducto',
                                        'detalles_venta.cantidad    as cantidadProducto',
                                        'detalles_venta.igv         as igvProducto',
                                        'detalles_venta.descuento   as descuentoProducto',
                                        'detalles_venta.subtotal    as subtotalProducto',
                                        'detalles_venta.total       as totalProducto'
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

    public function consultarXml($idVenta)
    {
        
        $venta = ventas::find($idVenta);
        $file   =   public_path($venta->xml);
        return Response::download($file);

    }


}
