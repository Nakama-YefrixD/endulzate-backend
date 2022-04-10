<?php

namespace App\Http\Controllers\ventas;

use Greenter\Ws\Services\SunatEndpoints;
use Greenter\See;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use NumerosEnLetras;

class FacturaEmision
{
	private $claveSol = [
		"ruc"        => "20000000001",
		"usuario"    => "MODDATOS",
		"contraseña" => "moddatos" 
	];
	private $see;
	private $client;
	private $address;
	private $company;
	private $invoice;
	private $items = [];
	private $resultado;

	public function __construct()
	{
		$this->configuracion();
		$this->setDireccionEmisor();
		$this->setCompañiaEmisor();
	}

	public function setCliente($cliente)
	{
		$this->client = (new Client())
    		->setTipoDoc($cliente['tipoDocumento'])
    		->setNumDoc($cliente['nroDocumento'])
    		->setRznSocial($cliente['razonSocial']);
	}

	public function setDireccionEmisor()
	{
		$this->address = (new Address())
    		->setUbigueo('040101')
    		->setDepartamento('AREQUIPA')
    		->setProvincia('AREQUIPA')
    		->setDistrito('AREQUIPA')
    		->setUrbanizacion('NONE')
    		->setDireccion('CAL. DEAN Cerro 410, 412, 4 NRO. --')
    		->setCodLocal('0000');
	}

	public function setCompañiaEmisor()
	{
		$this->company = (new Company())
    		->setRuc('20123456789')
    		->setRazonSocial('GREEN SAC')
    		->setNombreComercial('GREEN')
    		->setAddress($this->address);
	}

	public function setFactura($factura)
	{
		$this->invoice = (new Invoice())
    		->setUblVersion('2.1')
    		->setTipoOperacion('0101') // Venta - Catalog. 51
    		->setTipoDoc($factura['tipoComprobante'])
    		->setSerie($factura['serieVenta'])
    		->setCorrelativo($factura['facturaVenta'])
    		->setFechaEmision(new \DateTime(date('Y-m-d H:i:s')))
    		->setFormaPago(new FormaPagoContado())
    		->setTipoMoneda('PEN')
    		->setCompany($this->company)
    		->setClient($this->client)
    		->setMtoOperGravadas($factura['subTotalVenta'])
    		->setMtoIGV($factura['igvVenta'])
    		->setTotalImpuestos($factura['igvVenta'])
    		->setValorVenta($factura['subTotalVenta'])
    		->setSubTotal($factura['totalVenta'])
    		->setMtoImpVenta($factura['totalVenta']);
	}

	public function setDetalleVenta($articulo)
	{
		$this->items[] = (new SaleDetail())
    		->setCodProducto($articulo['codigo'])
    		->setUnidad('NIU') // Unidad - Catalog. 03
    		->setCantidad($articulo['cantidad'])
    		->setMtoValorUnitario($articulo['precio'] / 1.18)
    		->setDescripcion($articulo['nombre'])
    		->setMtoBaseIgv($articulo['subtotal'])
    		->setPorcentajeIgv(18.00)
    		->setIgv($articulo['subtotal'] * 0.18)
    		->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
    		->setTotalImpuestos($articulo['subtotal'] * 0.18) 
    		->setMtoValorVenta($articulo['subtotal'])
    		->setMtoPrecioUnitario($articulo['precio'] - $articulo['descuento']);
	}

	public function setLeyenda($total)
	{
		$legend = (new Legend())
    		->setCode('1000') // Monto en letras - Catalog. 52
    		->setValue(NumerosEnLetras::convertir($total).'/100 SOLES');

    	$this->invoice->setDetails($this->items)->setLegends([$legend]);
	}

	public function enviar()
	{
		$this->resultado = $this->see->send($this->invoice);
	}

	public function guardarXml($ventaId)
	{
		$xmlDireccion = '\sunat\xml\venta-'.$ventaId.'-'.$this->invoice->getName().'.xml';
		file_put_contents(public_path($xmlDireccion), $this->see->getFactory()->getLastXml());

		return $xmlDireccion;
	}

	public function guardarCdr($ventaId)
	{
		$cdrDireccion = '\sunat\zip\venta-'.$ventaId.'-R-'.$this->invoice->getName().'.zip';
		file_put_contents(public_path($cdrDireccion), $this->resultado->getCdrZip());

		return $cdrDireccion;
	}

	public function verificarFacturaEnviada()
	{
		if (!$this->resultado->isSuccess()) {
   			//echo 'Codigo Error: '.$result->getError()->getCode();
   			throw new \Exception($this->resultado->getError()->getMessage());
		}
	}

	public function getRespuestaFactura()
	{
		$cdr = $this->resultado->getCdrResponse();

		if (empty($cdr)) return null;

		return [
			'estado'		=> ((int)$cdr->getCode() === 0) ? 'aceptada' : 'rechazada',
			'obserbaciones' => (count($cdr->getNotes()) > 0) ? $cdr->getNotes() : null,
			'descripcion'	=> $cdr->getDescription()
		];
	}

	private function configuracion()
	{
		$this->see = new See();
		$this->see->setCertificate(file_get_contents(public_path('\sunat\certificadosfree\certificadofree.pem')));
		$this->see->setService(SunatEndpoints::FE_BETA);
		$this->see->setClaveSOL($this->claveSol['ruc'], $this->claveSol['usuario'], $this->claveSol['contraseña']);
	}
}