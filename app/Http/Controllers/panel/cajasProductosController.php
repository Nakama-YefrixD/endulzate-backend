<?php

namespace App\Http\Controllers\panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\cajasProductos;
use App\productos;
use App\tiposCajasProductos;
use App\almacenes;
use App\registrosCajas;

class cajasProductosController extends Controller
{
    public function consultaCaja(Request $request) 
    {
    	$idUsuario = $request->header('usuid');

    	if ($idUsuario == 1 || $idUsuario == 2) {
    		$idSucursal = 1;
    	} else {
    		$idSucursal = 2;
    	}

    	$codigo = $request->codigoCaja;
    	$caja_id = productos::where('codigo', $codigo)->first(['id', 'tipoCajaProducto_id as tipo']);
    	$producto_id = cajasProductos::where('cajaProducto_id', $caja_id['id'])->first('producto_id');

    	$caja = productos::where('productos.id', $caja_id['id'])
    						->join('almacenes', 'productos.id', '=', 'almacenes.producto_id')
    						->where([
    							['almacenes.sucursal_id', $idSucursal], 
    							['almacenes.producto_id', $caja_id['id']]
    						])
    						->join('cajasProductos', 'productos.id', '=', 'cajasProductos.cajaProducto_id')
    						->where('cajasProductos.cajaProducto_id', $caja_id['id'])
    						->first([
    							'productos.nombre',
    							'productos.precio',
    							'almacenes.stock',
    							'cajasProductos.cantidad'
    						]);

    	$producto = productos::where('productos.id', $producto_id['producto_id'])
    									->join('almacenes', 'productos.id', '=', 'almacenes.producto_id')
    									->where([
    										['almacenes.sucursal_id', $idSucursal], 
    										['almacenes.producto_id', $producto_id['producto_id']]
    									])
    									->first([
    										'productos.nombre',
    										'almacenes.stock',
    										'productos.precio'
    									]);

    	if ($caja != null && $producto != null && $caja_id['tipo'] == 3) {
    		return $consulta = ['response' => true, 'CAJA' => $caja, 'PRODUCTO' => $producto];
    	} else {
    		return $consulta = ['response' => false];
    	}
    }

    public function abrirCaja(Request $request)
    {
    	$idUsuario = $request->header('usuid');

        if ($idUsuario == 1 || $idUsuario == 2) {
            $idSucursal = 1;
        } else {
            $idSucursal = 2;
        }

    	$codigo = $request->codigoCaja;
    	$numero = $request->numAbrir;

    	$caja_id = productos::where('codigo', $codigo)->first(['id', 'cantidad', 'tipoCajaProducto_id as tipo']);
        
    	$caja_stock = almacenes::where([
    								['sucursal_id', $idSucursal], 
    								['producto_id', $caja_id['id']]
    							])->first(['id', 'stock']);

    	$producto_id = cajasProductos::where('cajaProducto_id', $caja_id['id'])
    									->first([
    										'producto_id as id', 
    										'cantidad'
    									]);

    	$prod_stock = almacenes::where([
    								['almacenes.sucursal_id', $idSucursal], 
    								['almacenes.producto_id', $producto_id['id']]
    							])
    							->join('productos', 'productos.id', '=', 'almacenes.producto_id')
    							->where('productos.id', $producto_id['id'])
    							->first([
                                    'almacenes.stock', 
                                    'almacenes.total as almacen', 
                                    'productos.cantidad', 
                                    'productos.total'
                                ]);
    							

    	if ($caja_stock['stock'] == 0 || $caja_stock['stock'] < $numero || $caja_id['tipo'] != 3) {

    		return $respuesta = ['response' => false, 'mensaje' => "No hay cajas disponibles"];

    	} else {
    		//Resta de la caja abierta
    		almacenes::where('id', $caja_stock['id'])->update(['stock' => $caja_stock['stock'] - $numero]);
    		productos::where('id', $caja_id['id'])->update(['cantidad' => $caja_id['cantidad'] - $numero]);

    		//Suma de los productos añadidos de la caja abierta
    		almacenes::where([['sucursal_id', $idSucursal], ['producto_id', $producto_id['id']]])
    					->update([
                            'stock' =>  $prod_stock['stock'] + ($numero * $producto_id['cantidad']),
                            'total' =>  $prod_stock['almacen'] + ($numero * $producto_id['cantidad'])
                        ]);
    		productos::where('id', $producto_id['id'])
    					->update([
                            'cantidad' => $prod_stock['cantidad'] + ($numero * $producto_id['cantidad']),
                            'total'    => $prod_stock['total'] + ($numero * $producto_id['cantidad'])  
                        ]);

            $registrocaja = new registrosCajas();
            $registrocaja->sucursal_id = $idSucursal;
            $registrocaja->usuario_id = $idUsuario;
            $registrocaja->cajaProducto_id = $caja_id->id;
            $registrocaja->cantidad = $numero;
            $registrocaja->accion = 'ABRIR';
            $registrocaja->save();

    		return $respuesta = ['response' => true, 'mensaje' => "Productos añadidos exitosamente"];
    	}	
    }

    public function cerrarCaja(Request $request)
    {
        $idUsuario = $request->header('usuid');

        if ($idUsuario == 1 || $idUsuario == 2) {
            $idSucursal = 1;
        } else {
            $idSucursal = 2;
        }

        $codigo = $request->codigoCaja;
        $numero = $request->numAbrir;

        $caja_id = productos::where('codigo', $codigo)->first(['id', 'cantidad', 'tipoCajaProducto_id as tipo']);
        
        $caja_stock = almacenes::where([
                                    ['sucursal_id', $idSucursal], 
                                    ['producto_id', $caja_id['id']]
                                ])->first(['id', 'stock']);

        $producto_id = cajasProductos::where('cajaProducto_id', $caja_id['id'])
                                        ->first([
                                            'producto_id as id', 
                                            'cantidad'
                                        ]);

        $prod_stock = almacenes::where([
                                    ['almacenes.sucursal_id', $idSucursal], 
                                    ['almacenes.producto_id', $producto_id['id']]
                                ])
                                ->join('productos', 'productos.id', '=', 'almacenes.producto_id')
                                ->where('productos.id', $producto_id['id'])
                                ->first([
                                    'almacenes.stock', 
                                    'almacenes.total as almacen', 
                                    'productos.cantidad', 
                                    'productos.total'
                                ]);
                                
        if ($prod_stock->stock < $numero * $producto_id->cantidad || $caja_id['tipo'] != 3) {

            return $respuesta = ['response' => false, 'mensaje' => "No hay productos disponibles"];

        } else {
            //Suma de la caja abierta
            almacenes::where('id', $caja_stock['id'])->update(['stock' => $caja_stock['stock'] + $numero]);
            productos::where('id', $caja_id['id'])->update(['cantidad' => $caja_id['cantidad'] + $numero]);

            //Resta de los productos de la caja cerrada
            almacenes::where([['sucursal_id', $idSucursal], ['producto_id', $producto_id['id']]])
                        ->update([
                            'stock' =>  $prod_stock['stock'] - ($numero * $producto_id['cantidad']),
                            'total' =>  $prod_stock['almacen'] - ($numero * $producto_id['cantidad'])
                        ]);
            productos::where('id', $producto_id['id'])
                        ->update([
                            'cantidad' => $prod_stock['cantidad'] - ($numero * $producto_id['cantidad']),
                            'total'    => $prod_stock['total'] - ($numero * $producto_id['cantidad'])  
                        ]);

            $registrocaja = new registrosCajas();
            $registrocaja->sucursal_id = $idSucursal;
            $registrocaja->usuario_id = $idUsuario;
            $registrocaja->cajaProducto_id = $caja_id->id;
            $registrocaja->cantidad = $numero;
            $registrocaja->accion = 'CERRAR';
            $registrocaja->save();

            return $respuesta = ['response' => true, 'mensaje' => "Caja cerrada exitosamente"];
        }
    }
}
