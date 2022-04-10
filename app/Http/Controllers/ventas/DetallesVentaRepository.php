<?php

namespace App\Http\Controllers\ventas;

use App\detallesVentas;

class DetallesVentaRepository
{
	public function save($detalleVenta, $ventaId)
	{
		$detallVenta = new detallesVentas;
        $detallVenta->venta_id    = $ventaId;
        $detallVenta->producto_id = $detalleVenta['productoId'];
        $detallVenta->cantidad    = $detalleVenta['cantidad'];
        $detallVenta->igv         = $detalleVenta['total'] - $detalleVenta['subtotal'];
        $detallVenta->descuento   = $detalleVenta['descuento'];
        $detallVenta->subtotal    = $detalleVenta['subtotal'];
        $detallVenta->total       = $detalleVenta['total'];
        $detallVenta->save();

        return $detallVenta;
	}
}