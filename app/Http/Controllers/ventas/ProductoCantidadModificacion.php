<?php

namespace App\Http\Controllers\ventas;

use App\productos;

class ProductoCantidadModificacion
{
	public function modificarCantidadProducto($id, $cantidad)
	{
		$producto = $this->buscarProducto($id);

		$producto->cantidad = $producto->cantidad - $cantidad;
		$producto->vendido  = $producto->vendido + $cantidad;
		$producto->update();
	}

	private function buscarProducto($id)
	{
		return productos::find($id);
	}
}