<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\productos;
use App\marcas;
use App\tipos;
use App\entradas;
use App\productosEntrada;
use App\control;

class agregarProductoController extends Controller
{
    public function buscarCodigo(Request $request)
    {
        $productos = productos::join('marcas as m', 'm.id', '=', 'productos.marca_id')
                                ->join('tipos as t', 't.id', '=', 'productos.tipo_id')
                                ->where(function ($query) use($request) {
                                    if($request['texto'] != ''){
                                            $query->where('productos.codigo', 'like', '%' . $request['texto'] . '%');
                                        }
                                    })
                                ->limit(5)
                                ->get([
                                    'productos.id     as idProducto',
                                    'productos.nombre as nombreProducto',
                                    'productos.codigo as codigoProducto',
                                    'm.id             as idMarca',
                                    't.id             as idTipo',
                                    'm.nombre         as nombreMarca',
                                    't.nombre         as nombreTipo',
                                    'productos.precio as precioProducto'

                                ]);

        if (sizeof($productos) > 0){
            return json_encode(
                array(
                    "code" => true, 
                    "result"=>$productos
                    )
            );
        }else{
            return json_encode(
                array(
                    "code" => false, 
                    )
            );
        }

    }

    public function buscarCodigoQr(Request $request)
    {
        $codigo = $request['codigoqr'];
        $producto = productos::join('marcas as m', 'm.id', '=', 'productos.marca_id')
                                ->join('tipos as t', 't.id', '=', 'productos.tipo_id')
                                ->where('productos.codigo', $codigo)
                                ->first([
                                    'productos.id     as idProducto',
                                    'productos.nombre as nombreProducto',
                                    'productos.codigo as codigoProducto',
                                    'm.id             as idMarca',
                                    't.id             as idTipo',
                                    'm.nombre         as nombreMarca',
                                    't.nombre         as nombreTipo',
                                    'productos.precio as precioProducto'

                                ]);

        if ($producto){
            return json_encode(
                array(
                    "code"   => true, 
                    "result" => $producto
                    )
            );
        }else{
            return json_encode(
                array(
                    "code" => false, 
                    )
            );
        }

    }

    public function buscarNombre(Request $request)
    {
        $productos = productos::join('marcas as m', 'm.id', '=', 'productos.marca_id')
                                ->join('tipos as t', 't.id', '=', 'productos.tipo_id')
                                ->where(function ($query) use($request) {
                                    if($request['texto'] != ''){
                                            $query->where('productos.nombre', 'like', '%' . $request['texto'] . '%');
                                        }
                                    })
                                ->limit(5)
                                ->get([
                                    'productos.id     as idProducto',
                                    'productos.nombre as nombreProducto',
                                    'productos.codigo as codigoProducto',
                                    'm.id             as idMarca',
                                    't.id             as idTipo',
                                    'm.nombre         as nombreMarca',
                                    't.nombre         as nombreTipo',
                                    'productos.precio as precioProducto'

                                ]);

        if (sizeof($productos) > 0){
            return json_encode(
                array(
                    "code" => true, 
                    "result"=>$productos
                    )
            );
        }else{
            return json_encode(
                array(
                    "code" => false, 
                    )
            );
        }

    }

    public function buscarMarca(Request $request)
    {
        $marcas = marcas::where(function ($query) use($request) {
                                    if($request['texto'] != ''){
                                            $query->where('nombre', 'like', '%' . $request['texto'] . '%');
                                        }
                                    })
                                ->limit(5)
                                ->get([
                                    'marcas.id      as idMarca',
                                    'marcas.nombre  as nombreMarca'
                                ]);

        if (sizeof($marcas) > 0){
            return json_encode(
                array(
                    "code" => true, 
                    "result"=>$marcas
                    )
            );
        }else{
            return json_encode(
                array(
                    "code" => false, 
                    )
            );
        }

    }

    public function buscarTipos(Request $request)
    {
        $tipos = tipos::where(function ($query) use($request) {
                                    if($request['texto'] != ''){
                                            $query->where('nombre', 'like', '%' . $request['texto'] . '%');
                                        }
                                    })
                                ->limit(5)
                                ->get([
                                    'tipos.id      as idTipo',
                                    'tipos.nombre  as nombreTipo'
                                ]);

        if (sizeof($tipos) > 0){
            return json_encode(
                array(
                    "code" => true, 
                    "result"=>$tipos
                    )
            );
        }else{
            return json_encode(
                array(
                    "code" => false, 
                    )
            );
        }
    }

    public function agregarProductoExistente(Request $request)
    {
        date_default_timezone_set("America/Mexico_City");
        $fechaActual = date('Y-m-d');

        $idProducto     = $request['idProducto'];
        $precioCompra   = $request['precioCompra'];
        $cantidad       = $request['cantidad'];
        $code           = true;
        $entrada = entradas::where('factura','=', 'MOVIL')->first();

        if($entrada){

        }else{
            $entrada = new entradas;
            $entrada->proveedor_id  = 1;
            $entrada->factura       = "MOVIL";
            $entrada->ruc           = '4545';
            $entrada->fecha         = '2019-10-21';
            if($entrada->save()){

            }else{
                return json_encode(array("code" => false ));
            }
        }
        $productosEntrada = new productosEntrada;
        $productosEntrada->producto_id  = $idProducto;
        $productosEntrada->entrada_id   = $entrada->id;
        $productosEntrada->precio       = $precioCompra;
        $productosEntrada->cantidad     = $cantidad;

        if($productosEntrada->save()){
            $control = new control;
            $control->user_id = 1;
            $control->metodo = "crear";
            $control->tabla = "productosEntrada";
            $control->campos = "producto_id, entrada_id, precio, cantidad";
            $control->datos = $idProducto.",".$entrada->id.",".$precioCompra.",".$cantidad;
            $control->descripcion = "Crear los productos que tiene una entrada";
            $control->save();

            $producto = Productos::find($idProducto);
            $producto->cantidad = $producto->cantidad + $cantidad;
            $producto->total = $producto->total + $cantidad;
            
            if($producto->update()){
                $control = new control;
                $control->user_id = 1;
                $control->metodo = "actualizar";
                $control->tabla = "Productos";
                $control->campos = "cantidad";
                $control->datos = $cantidad;
                $control->descripcion = "Actualizar la cantidad de productos";
                $control->save();    
            }else{
                return json_encode(array("code" => false ));
            }
        }else{
            return json_encode(array("code" => false ));
        }

        return json_encode(array("code" => true ));

    }

    public function nuevoProducto(Request $request)
    {
        date_default_timezone_set("America/Mexico_City");
        $fechaActual = date('Y-m-d');
        
        $codigoProducto = $request['codigoProducto']; 
        $nombreProducto = $request['nombreProducto']; 
        $nombreMarca    = $request['nombreMarca'];
        $tipoProducto   = $request['tipoProducto'];
        $precioCompra   = $request['precioCompra'];
        $precioVenta    = $request['precioVenta'];
        $cantidad       = $request['cantidad'];

        $entrada = entradas::where('factura','=', 'MOVIL')->first();

        if($entrada){

        }else{
            $entrada = new entradas;
            $entrada->proveedor_id  = 1;
            $entrada->factura       = "MOVIL";
            $entrada->ruc           = '4545';
            $entrada->fecha         = '2019-10-21';
            if($entrada->save()){

            }else{
                return json_encode(array("code" => false ));
            }
        }

        $producto = productos::where('codigo', '=', $codigoProducto)->first();
        if($producto){
            
        }else{
            $marca = marcas::where('nombre', $nombreMarca)->first();
            if($marca){

            }else{
                $marca = new marcas;
                $marca->nombre = $nombreMarca;
                $marca->save();
            }

            $tipo = tipos::where('nombre', $tipoProducto)->first();

            if($tipo){

            }else{
                $tipo = new tipos;
                $tipo->nombre = $tipoProducto;
                $tipo->save();
            }
            $producto = new productos;
            $producto->marca_id     = $marca->id;
            $producto->tipo_id      = $tipo->id;
            $producto->nombre       = $nombreProducto;
            $producto->cantidad     = 0;
            $producto->total        = 0;
            $producto->vendido      = 0;
            $producto->precio       = $precioVenta;
            $producto->precioVista  = "S/".$precioVenta;
            $producto->codigo       = $codigoProducto;
            $producto->save();
        }

        $productosEntrada = new productosEntrada;
        $productosEntrada->producto_id  = $producto->id;
        $productosEntrada->entrada_id   = $entrada->id;
        $productosEntrada->precio       = $precioCompra;
        $productosEntrada->cantidad     = $cantidad;

        if($productosEntrada->save()){
            $control = new control;
            $control->user_id = 1;
            $control->metodo = "crear";
            $control->tabla = "productosEntrada";
            $control->campos = "producto_id, entrada_id, precio, cantidad";
            $control->datos = $producto->id.",".$entrada->id.",".$precioCompra.",".$cantidad;
            $control->descripcion = "Crear los productos que tiene una entrada";
            $control->save();

            $producto = Productos::find($producto->id);
            $producto->cantidad = $producto->cantidad + $cantidad;
            $producto->total    = $producto->total + $cantidad;
            if($producto->update()){
                $control = new control;
                $control->user_id = 1;
                $control->metodo = "actualizar";
                $control->tabla = "Productos";
                $control->campos = "cantidad";
                $control->datos = $cantidad;
                $control->descripcion = "Actualizar la cantidad de productos";
                $control->save();    
            }else{
                return json_encode(array("code" => false ));
            }
        }else{
            return json_encode(array("code" => false ));
        }
        
        return json_encode(array("code" => true ));
    }
}
