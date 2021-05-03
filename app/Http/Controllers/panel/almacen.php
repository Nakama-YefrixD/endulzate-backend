<?php

namespace App\Http\Controllers\panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\productos;
use App\proveedores;
use App\entradas;
use App\productosEntradas;
use App\control;
use App\marcas;
use App\Tipos;
use App\almacenes;
use App\cajasProductos;
use Yajra\DataTables\DataTables;
use DB;

class almacen extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        $proveedores = proveedores::all();
        $productos = productos::all();
        $marcas = marcas::all();
        $tipos = Tipos::all();
        $data = array(
            'proveedores' => $proveedores,
            'productos' => $productos,
            'marcas' => $marcas,
            'tipos' => $tipos,
        );

        return view('react.app');
        // return view('react.app');
    }

    public function tb_almacen(Request $request)
    {
	    $idUsuario = auth()->id();
        if($idUsuario == 1 || $idUsuario == 2){
            // $idSucursal                  = env('sucursalId');
            $idSucursal = 1;
        }else{
            $idSucursal = 2;
        }

        return productos::join('marcas', 'productos.marca_id', '=', 'marcas.id')
                                ->join('tipos', 'tipos.id', '=', 'productos.tipo_id')
				->join('almacenes', 'productos.id', '=', 'almacenes.producto_id')
                                ->where('almacenes.sucursal_id', $idSucursal)
                                ->where(function ($query) use($request) {
                                    if($request->get('bcodigo') != '') {
                                        $query->where('productos.codigo', 'like',  $request->get('bcodigo') . '%');
                                    }

                                    if($request->get('bmarca') != '') {
                                        $query->where('marcas.nombre', 'like', '%' . $request->get('bmarca') . '%');
                                    }

                                    if($request->get('btipo') != '') {
                                        $query->where('tipos.nombre', 'like', '%' . $request->get('btipo') . '%');
                                    }

                                    if($request->get('bnombre') != '') {
                                        $query->where('productos.nombre', 'like', '%' . $request->get('bnombre') . '%');
                                    }
                                })->paginate(10, array(
                                    'productos.id as idProducto',
                                    'productos.codigo as codigoProducto',
                                    'marcas.id as idMarca',
                                    'marcas.nombre as nombreMarca',
                                    'tipos.id as idTipo',
                                    'tipos.nombre as nombreTipo',
                                    'productos.nombre as nombreProducto',
                                    'almacenes.stock as cantidadProducto',
                                    'productos.precio as precioProducto'
                                ));
    }

    public function entradaCrear(Request $request)
    {
        $idUsuario = auth()->id();
        if($idUsuario == 1 || $idUsuario == 2){
            // $idSucursal                  = env('sucursalId');
            $idSucursal = 1;
        }else{
            $idSucursal = 2;
        }

        DB::beginTransaction();
        try {
            $entrada                = new entradas;
            $entrada->sucursal_id   = $idSucursal;
            $entrada->proveedor_id  = $request['proveedor'];
            $entrada->factura       = $request['factura'];
            $entrada->fecha         = $request['fecha'];

            $datosProducto = $request['producto'];
            if($entrada->save()) {


                for ($x = 0; $x < count($datosProducto); $x++) {
                    $productosEntrada               = new productosEntradas;
                    $productosEntrada->producto_id  = $request['producto'][$x];
                    $productosEntrada->entrada_id   = $entrada->id;
                    $productosEntrada->precio       = $request['precio'][$x];
                    $productosEntrada->cantidad     = $request['cantidad'][$x];

                    if($productosEntrada->save()){


                        $producto           = productos::find($request['producto'][$x]);
                        $producto->cantidad = $producto->cantidad   + $request['cantidad'][$x];
                        $producto->total    = $producto->total      + $request['cantidad'][$x];
                        if($producto->update()){


                            $almacen = almacenes::where('producto_id', $request['producto'][$x])
                                                ->where('sucursal_id', $idSucursal)
                                                ->first();
                            if($almacen){
                                $almacen->stock         = $almacen->stock  + $request['cantidad'][$x];
                                $almacen->total         = $almacen->total  + $request['cantidad'][$x];
                                $almacen->update();
                            }else{
                                $almacen = new almacenes;
                                $almacen->sucursal_id   = $idSucursal;
                                $almacen->producto_id   = $request['producto'][$x];
                                $almacen->stock         = $request['cantidad'][$x];
                                $almacen->vendido       = 0;
                                $almacen->total         = $request['cantidad'][$x];
                                $almacen->save();
                            }

                        }
                    }
                }
            }

            DB::commit();

            $rpta = array(
                'response'      =>  true,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            //echo $datosProducto;
            echo json_encode($e->getMessage());

        }
    }

    public function proveedorCreear(Request $request)
    {
        DB::beginTransaction();
        try {

            $proveedores = new Proveedores;
            $proveedores->nombre = $request['nombreProveedor'];
            $proveedores->ruc = $request['rucProveedor'];
            $proveedores->numero = $request['telefonoProveedor'];
            $proveedores->direccion = $request['direccionProveedor'];
            $proveedores->tipo = $request['tipoProveedor'];

            if($proveedores->save()) {

            }

            DB::commit();

            $rpta = array(
                'response'      =>  true,
                'idProveedor'      =>  $proveedores->id,
                'nombreProveedor'      =>  $proveedores->nombre,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }

    public function marcaCrear(Request $request)
    {
        DB::beginTransaction();
        try {

            $marcas = new Marcas;
            $marcas->nombre = $request['nombreMarca'];

            if($marcas->save()) {

            }

            DB::commit();

            $rpta = array(
                'response'      =>  true,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }

    public function tipoCrear(Request $request)
    {
        DB::beginTransaction();
        try {

            $tipos = new Tipos;
            $tipos->nombre = $request['nuevoTipoProducto'];

            if($tipos->save()) {

            }

            DB::commit();

            $rpta = array(
                'response'      =>  true,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }

    public function productoCrear(Request $request)
    {
        DB::beginTransaction();
        try {
            $precioVenta = explode("S/", $request['precioVentaProducto']);

            if(sizeof($precioVenta) > 1){
                $prePrecioVenta = $precioVenta[1];
            }else{
                $prePrecioVenta = $precioVenta[0];
            }

            $precioVentaEntero = explode(",", $prePrecioVenta);
            if(sizeof($precioVentaEntero) > 1){
                $precioVentaFinal = $precioVentaEntero[0].$precioVentaEntero[1];
            }else{
                $precioVentaFinal = $precioVentaEntero[0];
            }

            $productos = new Productos;
            $productos->codigo      = $request['codigoProductoNuevo'];
            $productos->marca_id    = $request['marcaProducto'];
            $productos->tipo_id     = $request['tipoProducto'];
            $productos->nombre      = $request['nombreProductoNuevo'];
            $productos->cantidad    = 0;
            $productos->total       = 0;
            $productos->vendido     = 0;
            $productos->precio      = $precioVentaFinal;
            $productos->tipoCajaProducto_id     = 1;

            if($productos->save()) {
                $idUsuario = auth()->id();
                if($idUsuario == 1 || $idUsuario == 2){
                    // $idSucursal                  = env('sucursalId');
                    $idSucursal = 1;
                    $segundaSucursal = 2;
                }else{
                    $idSucursal = 2;
                    $segundaSucursal = 1;
                }
                $almacen = new almacenes;
                $almacen->sucursal_id   = $idSucursal;
                $almacen->producto_id   = $productos->id;
                $almacen->stock         = 0;
                $almacen->vendido       = 0;
                $almacen->total         = 0;
                $almacen->save();

                $almacen = new almacenes;
                $almacen->sucursal_id   = $segundaSucursal;
                $almacen->producto_id   = $productos->id;
                $almacen->stock         = 0;
                $almacen->vendido       = 0;
                $almacen->total         = 0;
                $almacen->save();

            }

            DB::commit();

            $rpta = array(
                'response'          =>  true,
                'idProducto'        =>  $productos->id,
                'nombreProducto'    =>  $productos->nombre,
                'codigoProducto'    =>  $productos->codigo,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }

    public function cajaProductoCrear(Request $request)
    {
        $codigoCaja             = $request['codigoCaja'];
        $idProductoCaja         = $request['idProducto'];
        $tipoProductoCaja       = $request['tipoProducto'];
        $marcaProductoCaja      = $request['marcaProducto'];
        $cantidadProductoCaja   = $request['cantidadProducto'];
        $nombreCaja             = $request['nombreCaja'];
        $precioCaja             = $request['precioCaja'];

        DB::beginTransaction();
        try{
            $idUsuario = auth()->id();
            if($idUsuario == 1 || $idUsuario == 2){
                // $idSucursal                  = env('sucursalId');
                $idOtraSucursal = 2;
                $idSucursal     = 1;
            }else{
                $idOtraSucursal = 1;
                $idSucursal = 2;
            }
            $exisCajaProductos = productos::where('codigo', $codigoCaja)
                                        ->first();

            if($exisCajaProductos){
                $exisCajaProductos->marca_id             = $marcaProductoCaja;
                $exisCajaProductos->tipo_id              = $tipoProductoCaja;
                $exisCajaProductos->nombre               = $nombreCaja;
                $exisCajaProductos->precio               = $precioCaja;
                $exisCajaProductos->tipoCajaProducto_id  = 3;

                if($exisCajaProductos->update()){

                    $exisProductoAlmacen = almacenes::where('producto_id', $exisCajaProductos->id)
                                                    ->where('sucursal_id', $idSucursal)
                                                    ->first();
                    if($exisProductoAlmacen){

                    }else{
                        $almacen                = new almacenes;
                        $almacen->sucursal_id   = $idSucursal;
                        $almacen->producto_id   = $exisCajaProductos->id;
                        $almacen->stock         = 0;
                        $almacen->vendido       = 0;
                        $almacen->total         = 0;
                        $almacen->save();
                    }

                    $exisProductoAlmacen = almacenes::where('producto_id', $exisCajaProductos->id)
                                                    ->where('sucursal_id', $idOtraSucursal)
                                                    ->first();
                    if($exisProductoAlmacen){

                    }else{
                        $almacen                = new almacenes;
                        $almacen->sucursal_id   = $idOtraSucursal;
                        $almacen->producto_id   = $exisCajaProductos->id;
                        $almacen->stock         = 0;
                        $almacen->vendido       = 0;
                        $almacen->total         = 0;
                        $almacen->save();
                    }

                }else{
                    $repuesta = false;
                }

                $productoCaja                       = productos::find($idProductoCaja);
                $productoCaja->tipoCajaProducto_id  = 2;
                $productoCaja->update();

                $caja                   = new cajasProductos;
                $caja->cajaProducto_id  = $exisCajaProductos->id;
                $caja->producto_id      = $productoCaja->id;
                $caja->cantidad         = $cantidadProductoCaja;
                $caja->save();

            }else{
                $CajaProductos                       = new productos;
                $CajaProductos->codigo               = $codigoCaja;
                $CajaProductos->marca_id             = $marcaProductoCaja;
                $CajaProductos->tipo_id              = $tipoProductoCaja;
                $CajaProductos->nombre               = $nombreCaja;
                $CajaProductos->total                = 0;
                $CajaProductos->vendido              = 0;
                $CajaProductos->cantidad             = 0;
                $CajaProductos->precio               = $precioCaja;
                $CajaProductos->tipoCajaProducto_id  = 3;
                $CajaProductos->save();

                $almacen                = new almacenes;
                $almacen->sucursal_id   = $idSucursal;
                $almacen->producto_id   = $CajaProductos->id;
                $almacen->stock         = 0;
                $almacen->vendido       = 0;
                $almacen->total         = 0;
                $almacen->save();

                $almacen                = new almacenes;
                $almacen->sucursal_id   = $idOtraSucursal;
                $almacen->producto_id   = $CajaProductos->id;
                $almacen->stock         = 0;
                $almacen->vendido       = 0;
                $almacen->total         = 0;
                $almacen->save();

                $productoCaja                       = productos::find($idProductoCaja);
                $productoCaja->tipoCajaProducto_id  = 2;
                $productoCaja->update();

                $caja                   = new cajasProductos;
                $caja->cajaProducto_id  = $CajaProductos->id;
                $caja->producto_id      = $productoCaja->id;
                $caja->cantidad         = $cantidadProductoCaja;
                $caja->save();
            }
            DB::commit();
            $rpta = array(
                'response'          =>  true,
            );
            echo json_encode($rpta);
        }catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());

        }



    }

    public function productoEditar(Request $request)
    {
        DB::beginTransaction();
        try {
            $precioVenta = explode("S/", $request['editarPrecioVentaProducto']);

            if(sizeof($precioVenta) > 1){
                $prePrecioVenta = $precioVenta[1];
            }else{
                $prePrecioVenta = $precioVenta[0];
            }

            $precioVentaEntero = explode(",", $prePrecioVenta);
            if(sizeof($precioVentaEntero) > 1){
                $precioVentaFinal = $precioVentaEntero[0].$precioVentaEntero[1];
            }else{
                $precioVentaFinal = $precioVentaEntero[0];
            }

            //miomio
            $productos              = productos::find($request['editarIdProducto']);
            $productos->codigo      = $request['editarCodigoProductoNuevo'];
            $productos->marca_id    = $request['editarMarcaProducto'];
            $productos->tipo_id     = $request['editarTipoProducto'];
            $productos->nombre      = $request['editarNombreProductoNuevo'] ;
            $productos->precio      = $precioVentaFinal;

            if($productos->update()) {
                $response = true;
            }

            DB::commit();

            $rpta = array(
                'response'          =>  $response,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }

    public function productoEditarCantidad(Request $request)
    {
        DB::beginTransaction();
        try {
            $precioVenta = explode("S/", $request['editarPrecioVentaProducto']);

            if(sizeof($precioVenta) > 1){
                $prePrecioVenta = $precioVenta[1];
            }else{
                $prePrecioVenta = $precioVenta[0];
            }

            $precioVentaEntero = explode(",", $prePrecioVenta);
            if(sizeof($precioVentaEntero) > 1){
                $precioVentaFinal = $precioVentaEntero[0].$precioVentaEntero[1];
            }else{
                $precioVentaFinal = $precioVentaEntero[0];
            }

            $productos = productos::find($request['editarIdProducto']);
            $productos->codigo = $request['editarCodigoProductoNuevo'];
            $productos->marca_id = $request['editarMarcaProducto'];
            $productos->tipo_id = $request['editarTipoProducto'];
            $productos->nombre = $request['editarNombreProductoNuevo'] ;
            $productos->precio = $precioVentaFinal;

            if($request['editarCantidadProducto'] == $productos->cantidad){

            }else{
                $entradaEditar = entradas::find(10000);
                if($entradaEditar){
                    $idEntrada = $entradaEditar->id;
                }else{
                    $entrada = new entradas;
                    $entrada->id = 10000;
                    $entrada->proveedor_id = 1;
                    $entrada->factura = "01";
                    $entrada->fecha = "2019-08-14";
                    $entrada->save();
                    $idEntrada = $entrada->id;
                }

                $productosEntrada               = new productosEntradas;
                $productosEntrada->producto_id  = $productos->id;
                $productosEntrada->entrada_id   = $idEntrada;
                $productosEntrada->precio       = $request['editarPrecioCosto'];
                $productosEntrada->cantidad     = $request['editarCantidadProducto'] - $productos->cantidad;

                if($productosEntrada->save()){
                    $productos->cantidad        = $request['editarCantidadProducto'];
                    $productos->total           = $productos->total + $productosEntrada->cantidad;
                }
            }


            if($productos->update()) {
                $control = new control;
                $control->user_id       = auth()->id();
                $control->metodo        = "Editar";
                $control->tabla         = "productos";
                $control->campos        = "codigo, marca_id, tipo_id, nombre, precio";
                $control->datos         = $request['editarIdProducto'].', '. $request['editarCodigoProductoNuevo'].', '. $request['editarMarcaProducto'].', '. $request['editarTipoProducto'].', '. $request['editarNombreProductoNuevo'].', '. $request['editarPrecioVentaProducto'];
                $control->descripcion   = "Editar un producto";
                $control->save();
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

}
