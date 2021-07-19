<?php

namespace App\Http\Controllers;

use App\productos;
use App\productosEntradas;
use App\control;
use App\almacenes;
use App\cajasProductos;
use App\descuentosProductos;
use Illuminate\Http\Request;
use DB;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function index()
    {
        $productos = productos::all();
        return $productos;
    }

    public static function buscadorProductos()
    {
        $productos = productos::select( 'productos.id           as id',
                                        'productos.marca_id     as marca_id',
                                        'productos.tipo_id      as tipo_id',
                                        'productos.cantidad     as cantidad',
                                        'productos.precio       as precio',
                                        DB::raw("CONCAT(productos.codigo,'-',productos.nombre) AS nombre"),
                                        'dp.producto_id         as idProductoDescuento',
                                        'dp.porcentaje          as porcentajeProductoDescuento',
                                        'dp.cantidad            as cantidadProductoDescuento'
                                        )
                                ->leftjoin('descuentosProductos as dp', 'dp.producto_id', '=','productos.id')
                                ->get();
        return $productos;
    }
    public function codigo()
    {
        $productos = productos::all();
        foreach($productos as $producto){

            $producto = Productos::find($producto->id);
            $producto->codigo = $producto->id;
            $producto->update();

        }
    }

    public function eliminarProducto(Request $request)
    {
        $idUsuario = $request->header('usuid');
        if($idUsuario == 1 || $idUsuario == 2){
            // $idSucursal                  = env('sucursalId');
            $idSucursal = 1;
        }else{
            $idSucursal = 2;
        }
        DB::beginTransaction();
        try {

            $almacen            = almacenes::where('sucursal_id', $idSucursal)
                                            ->where('producto_id', $request['id'])
                                            ->first();
            $productos              = productos::find($request['id']);
            $productos->total       = $productos->total - $almacen->total ;
            $productos->vendido     = $productos->vendido - $almacen->vendido ;
            $productos->cantidad    = $productos->cantidad - $almacen->stock ;
            $productos->update();

            $almacen->delete();
            $productosEntrada = productosEntradas::join('entradas as e', 'e.id', 'productosEntradas.entrada_id')
                                                ->where('e.sucursal_id', $idSucursal)
                                                ->where('productosEntradas.producto_id', $request['id']);
            if($productosEntrada->delete()){

                
            }else{
                

            }

            DB::commit();

            $rpta = array(
                'response'          =>  true,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }

    public function buscarProducto(Request $request)
    {
        $idUsuario = $request->header('usuid');

    	if ($idUsuario == 1 || $idUsuario == 2) {
    		$idSucursal = 1;
    	} else {
    		$idSucursal = 2;
        }
        
        $producto = productos::join('almacenes as a', 'a.producto_id', 'productos.id')
                                ->where('productos.codigo', $request->codigoProducto)
                                ->where('a.sucursal_id', $idSucursal)
                                ->first([
                                    'productos.id                   as id',
                                    'productos.marca_id             as marca_id', 
                                    'productos.tipo_id              as tipo_id' ,
                                    'productos.codigo               as codigo' ,
                                    'productos.nombre               as nombre' ,
                                    'productos.total                as total' ,
                                    'productos.vendido              as vendico', 
                                    'a.stock                        as cantidad', 
                                    'productos.precio               as precio' ,
                                    'productos.created_at           as created_at', 
                                    'productos.updated_at           as updated_at', 
                                    'productos.tipoCajaProducto_id  as tipoCajaProducto_id'
                                ]);
        if($producto){

            $descuentosProducto = descuentosProductos::where('producto_id', $producto->id)
                                                      ->first();

            if($descuentosProducto){

                $variosDescuentos = descuentosProductos::where('producto_id', $producto->id)
                                                      ->get();

            }else{
                $descuentosProducto = 0;
                $variosDescuentos = 0;
            }

            $estado = true;


        }else{
            $estado = false;
            $descuentosProducto = 0;
            $variosDescuentos = 0;
        }

        $rpta = array(
            'response'         => $estado,
            'producto'         => $producto,
            'descuento'        => $descuentosProducto,
            'variosDescuentos' => $variosDescuentos,
        );

        echo json_encode($rpta);

    }

    public function buscarCajaProducto(Request $request)
    {
        $codigoCajaProducto = $request->codigoCajaProducto;
        $caja   =   productos::where('codigo', $codigoCajaProducto)
                                ->first();
        if($caja){
            $existe = true;
            if($caja->tipoCajaProducto_id == 1){
                $estado     = true;
                $mensaje    = "El producto escaneado ya existe, y esta disponible para ser asignado como una caja";
            }else if($caja->tipoCajaProducto_id == 2){
                $estado     = false;
                $mensaje    = "El producto escaneado esta asignado como un producto de una caja!";
            }else if($caja->tipoCajaProducto_id == 3){
                $estado     = false;
                $mensaje    = "El producto escaneado ya esta registrado como una caja";
            }else{
                $estado     = false;
                $mensaje    = "El producto escaneado no tiene asignado un rol de producto caja";
            }
        }else{
            $existe     = false;
            $estado     = true;
            $mensaje    = "El producto escaneado esta disponible para ser asignado como una caja";

        }

        $rpta = array(
            'response'  => $estado,
            'mensaje'   => $mensaje,
            'producto'  => $caja,
            'existe'    => $existe,

        );

        return json_encode($rpta);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\productos  $productos
     * @return \Illuminate\Http\Response
     */
    public function show(productos $productos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\productos  $productos
     * @return \Illuminate\Http\Response
     */
    public function edit(productos $productos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\productos  $productos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, productos $productos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\productos  $productos
     * @return \Illuminate\Http\Response
     */
    public function destroy(productos $productos)
    {
        //
    }
}
