<?php

namespace App\Http\Controllers\ventas;

use App\ventas;

class VentasRepository
{
	public function save($request, $clienteId)
	{
        $venta = new ventas;
        $venta->tipoComprobante_id = $request->input('tipoComprobante');
        $venta->cliente_id         = $clienteId;
        $venta->user_id            = $request->header('usuid');
        $venta->tipoMoneda_id      = $request->input('tipoMoneda');
        $venta->sucursal_id		   = 1;
        $venta->numero             = $request->input('facturaVenta');
        $venta->fecha              = $request->input('dateFactura');
        $venta->fechaVencimiento   = $request->input('dateFactura');
        $venta->descuento          = $request->input('descuentoVenta');
        $venta->igv                = 18;
        $venta->impuestos          = $request->input('igvVenta');
        $venta->subtotal           = $request->input('subTotalVenta');
        $venta->total              = $request->input('totalVenta');
        $venta->estadoEmail        = false;
        $venta->estadoSunat        = true;
        $venta->observaciones      = $request->input('observacionVenta');
        $venta->save();

        return $venta;
	}

    public function updateXml($ventaId, $xmlDireccion)
    {
    	$venta = ventas::find($ventaId);
    	$venta->xml = $xmlDireccion;
    	$venta->update();    	
    }

    public function updateCdr($ventaId, $cdrDireccion)
    {
    	$venta = ventas::find($ventaId);
    	$venta->cdr = $cdrDireccion;
    	$venta->update(); 
    }

    public function updateEstadoSunat($ventaId)
    {
    	$venta = ventas::find($ventaId);
    	$venta->estadoSunat = false;
    	$venta->update(); 
    }
}