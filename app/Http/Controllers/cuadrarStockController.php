<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\productos;
use App\detallesVentas;
use App\almacenes;
use App\ventas;

class cuadrarStockController extends Controller
{
    public function cuadrarStock(Request $request)
    {

        // CUANDO TU HACES UNA VENTA EN CAJA Y ESTA DEBERIA SER PRODUCTO

        
        // $codigoCaja = "";
        // $codigoPrdocuto = "";

        $sucursalId     = $request['sucursalid'];
        $productoCajaId = $request['productoCajaId'];
        $productoId     = $request['productoId'];
        $cantidadProductoReemplazar = $request['cantidadReemplazar'];
        $numeroVenta = $request['numeroVenta'];

        $productoCaja = productos::where('id', $productoCajaId)->first();

        if($productoCaja){

            $producto = productos::where('id', $productoId)->first();

            if($producto){

                $venta = ventas::where('numero', $numeroVenta)
                                ->where('sucursal_id', $sucursalId)
                                ->first();

                if($venta){

                    $detalleVenta = detallesVentas::where('venta_id', $venta->id)
                                                    ->where('producto_id', $productoCaja->id)
                                                    ->first();

                    if($detalleVenta){

                        $cantidadCajaAntes = $detalleVenta->cantidad;

                        $detalleVenta->producto_id = $productoId;
                        $detalleVenta->cantidad = $cantidadProductoReemplazar;
                        if($detalleVenta->update()){

                            $almacenCaja = almacenes::where('producto_id', $productoCaja->id)
                                                    ->where('sucursal_id', $sucursalId)
                                                    ->first();

                            if($almacenCaja){
                                $almacenCaja->stock = $almacenCaja->stock + $cantidadCajaAntes;
                                $almacenCaja->vendido = $almacenCaja->vendido - $cantidadCajaAntes;
                                if($almacenCaja->update()){

                                    $almacenProducto = almacenes::where('producto_id', $producto->id)
                                                                ->where('sucursal_id', $sucursalId)
                                                                ->first();

                                    if($almacenProducto){

                                        $almacenProducto->stock = $almacenProducto->stock - $cantidadProductoReemplazar;
                                        $almacenProducto->vendido = $almacenProducto->vendido + $cantidadProductoReemplazar;
                                        if($almacenProducto->update()){
                                            echo "SE ACTUALIZO CORRECTAMENTE";
                                        }

                                    }else{
                                        echo "No se encontro el almacen del producto";
                                    }

                                }else{
                                    echo "No se pudo actualizar el almacen de la caja";
                                }
                            }else{
                                echo "No se encontro el almacen de la caja del producto";
                            }

                        }else{
                            echo "No se pudo actualizar el detalle de la venta";
                        }

                    }else{
                        echo "no existe el detalle de la venta";
                    }

                }else{
                    echo "no existe la venta";
                }

            }else{
                echo "no existe ese producto";
            }



        }else{
            echo "no existe ese producto";
        }




    }
}
