<?php

namespace App\Http\Controllers\ventas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\productos;
use App\tiposDocumentos;
use App\tiposComprobantes;

class VentasController extends Controller
{
	private $clienteRepository;
	private $ventasRepository;
	private $detallesVentaRepository;
	private $productoCantidadModificacion;

	public function __construct()
	{
		$this->clienteRepository = new ClienteRepository;
		$this->ventasRepository  = new VentasRepository;
		$this->detallesVentaRepository = new DetallesVentaRepository;
		$this->productoCantidadModificacion = new ProductoCantidadModificacion;
	}

	public function store(Request $request)
	{
		$cliente = $this->clienteRepository->findByNroDocumento($request->input('numeroDocumento'));

		if (!$cliente) {
			$cliente = $this->clienteRepository->save($request);
		}

		$venta = $this->ventasRepository->save($request, $cliente->id);

		foreach ($request->input('detallesVenta') as $detalleVenta) {
			$this->detallesVentaRepository->save($detalleVenta, $venta->id);
			$this->productoCantidadModificacion->modificarCantidadProducto($detalleVenta['productoId'], $detalleVenta['cantidad']);
		}

		$tipoDocumento    = tiposDocumentos::find($request->input('tipoDocumento'));
        $tiposcomprobante = tiposComprobantes::find($request->input('tipoComprobante'));

		$factura = new FacturaEmision();
		$factura->setCliente([
			'tipoDocumento' => $tipoDocumento->codigo,
    		'nroDocumento'  => $request->input('numeroDocumento'),
    		'razonSocial'   => $request->input('nombreCliente')
		]);
		$factura->setFactura([
			'tipoComprobante' => $tiposcomprobante->codigo,
    		'serieVenta'	  => $request->input('serieVenta'),
    		'facturaVenta'	  => $request->input('facturaVenta'),
			'subTotalVenta'   => $venta->subtotal,
    		'igvVenta'        => $venta->impuestos, 
    		'totalVenta'      => $venta->total
		]);

		foreach ($request->input('detallesVenta') as $detalleVenta) {
			$producto = productos::find($detalleVenta['productoId']);

			$factura->setDetalleVenta([
				'codigo'	 => $producto->codigo,
				'nombre'	 => $producto->nombre,
				'cantidad'	 => $detalleVenta['cantidad'],
				'precio'	 => $producto->precio,
				'descuento'  => $detalleVenta['descuento'],
				'subtotal'   => $detalleVenta['subtotal']
			]);
		}

		$factura->setLeyenda($venta->total);
		$factura->enviar();
		$xmlDireccion = $factura->guardarXml($venta->id);
		$this->ventasRepository->updateXml($venta->id, $xmlDireccion);

		try {
			$factura->verificarFacturaEnviada();
			$cdrDireccion = $factura->guardarCdr($venta->id);
			$this->ventasRepository->updateCdr($venta->id, $cdrDireccion);
		} catch (\Exception $e) {
			$this->ventasRepository->updateEstadoSunat($venta->id);
			$mensajeError = $e->getMessage();
		}

		return response()->json([
			'facturaEnviada' => (isset($mensajeError)) ? $mensajeError : true,
			'facturaEstado'  => $factura->getRespuestaFactura() 
		]);
	} 
}