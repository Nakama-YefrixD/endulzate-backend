<?php

namespace App\Http\Controllers\ventas;

use App\clientes;

class ClienteRepository
{
	public function findByNroDocumento($nroDocumento)
	{
		return clientes::where('documento', $nroDocumento)->first();
	}

	public function save($request)
	{
		$cliente = new clientes;
        $cliente->tipoDocumento_id = $request->input('tipoDocumento');
        $cliente->documento        = $request->input('numeroDocumento');
        $cliente->nombre           = $request->input('nombreCliente');
        $cliente->save();

        return $cliente;
	}
}