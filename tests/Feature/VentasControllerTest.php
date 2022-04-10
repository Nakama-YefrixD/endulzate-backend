<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VentasControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStore()
    {
    	$this->withoutExceptionHandling();

    	$request = [
    		'tipoDocumento'    => 1,
        	'numeroDocumento'  => "15684870",
        	'nombreCliente'	   => "Pepeneitor",
        	'tipoComprobante'  => 2,
       	    'tipoMoneda'       => 1,
       	    'serieVenta'	   => 'B001',
        	'facturaVenta'     => '1',
        	'dateFactura'	   => '2020-08-24 13:05:00',
        	'descuentoVenta'   => 0,
        	'igvVenta'		   => 18,
        	'subTotalVenta'    => 100,
        	'totalVenta'	   => 118,
        	'observacionVenta' => "pipipi pipipi",
        	'detallesVenta'    => [
        		[
        			'productoId' => 275,
        			'cantidad'   => 1,
        			'descuento'  => 0,
        			'subtotal'   => 100,
        			'total'      => 118
        		]
        	],

    	];

    	$response =  $this->withHeaders(['usuid' => 2])
    		->json('POST', 'api/ventas', $request)
			->assertStatus(200)
    		->decodeResponseJson();

    	//echo "\xA";
    	//print_r($response);
    }
}
