<?php

namespace App\Http\Controllers\panel\Configuraciones;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\descuentosProductos;
use App\productos;
use App\Control;
use App\usuariosSucursales;
use Yajra\DataTables\DataTables;
use DB;

class descuentosController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        $productos = productos::all();
        $data = array(
            'productos' => $productos,
        );
        return view('configuracion.descuentos.index')->with($data);
    }

    public function tb_descuentos(Request $request)
    {   

        // $idUsuario  =   $request['idUsuario']; 
        $idUsuario  = auth()->id();
        $idSucursal = $request['idSucursal'];
        
        $sucursalusuarioPredeterminado = 0;
        $sucursalesUsuario = '';

        if($idSucursal){
            if($idSucursal == 0){
                $sucursalesUsuario  = usuariosSucursales::where('user_id', $idUsuario)
                                                        ->get([
                                                            'sucursal_id'
                                                        ]);
            }
        }else{
            $sucursalusuarioPredeterminado  = usuariosSucursales::where('user_id', $idUsuario)
                                                                ->where('predeterminado', 1)
                                                                ->first([
                                                                    'sucursal_id'
                                                                ]);
            if(!$sucursalusuarioPredeterminado){
                $sucursalusuarioPredeterminado = 0;
                
            }else{
                $sucursalusuarioPredeterminado = $sucursalusuarioPredeterminado->sucursal_id;
            }
        }

        $descuentos = descuentosProductos::join('productos as p', 'p.id', '=', 'descuentosProductos.producto_id')
                                            ->where(function ($query) use( $sucursalesUsuario , $idSucursal, $sucursalusuarioPredeterminado ) {
                                                if($idSucursal){
                                                    if($idSucursal == 0){
                                                        if(sizeof($sucursalesUsuario) > 0 ){
                                                            foreach($sucursalesUsuario as $sucursalUsuario){
                                                                $query->where('descuentosProductos.sucursal_id', $sucursalUsuario->sucursal_id);
                                                            }
                                                        }else{
                                                            $query->where('descuentosProductos.sucursal_id', 0);
                                                        } 
                                                    }else{
                                                        $query->where('descuentosProductos.sucursal_id', $idSucursal);
                                                    }

                                                }else{
                                                    $query->where('descuentosProductos.sucursal_id', $sucursalusuarioPredeterminado);
                                                }
                                            })
                                            ->paginate(10, array(
                                                'p.id                           as idProductos',
                                                'descuentosProductos.id         as idDescuentos',
                                                'p.codigo                       as codigoProductos',
                                                'p.nombre                       as nombreProductos',
                                                'descuentosProductos.cantidad   as cantidadDescuentos',
                                                'descuentosProductos.porcentaje as porcentajeDescuentos',
                                                'descuentosProductos.nuevoPrecio as nuevoPrecioDescuentos',
                                                'p.precio                       as precioProductos'
                                            ));


        $rpta = array(
            'respuesta'     => true,
            'tb_descuentos' => $descuentos
        );
        return json_encode($rpta);
        
        
    }

    public function descuentoCrear(Request $request)
    {

        $idProductoEscaneado         = $request['idProducto'];
        $precioProductoEscaneado     = $request['precioProducto'];
        $nuevoPrecioOferta           = $request['nuevoPrecio'];
        $cantidadOferta              = $request['cantidad'];
        $idUsuario = auth()->id();
        if($idUsuario == 1 || $idUsuario == 2){
            // $idSucursal                  = env('sucursalId');
            $idSucursal = 1;
        }else{
            $idSucursal = 2;
        }
        
        $porcentaje = (100*$nuevoPrecioOferta)/$precioProductoEscaneado;

        DB::beginTransaction();
        try {
            $existDescuento = descuentosProductos::where('producto_id', $idProductoEscaneado)
                                                ->where('cantidad', $cantidadOferta)
                                                ->where('sucursal_id', $idSucursal)
                                                ->first();
            if(!$existDescuento){
                $descuento              = new descuentosProductos;
                $descuento->sucursal_id = $idSucursal;
                $descuento->producto_id = $idProductoEscaneado; 
                $descuento->porcentaje  = round($porcentaje);
                $descuento->nuevoPrecio = $nuevoPrecioOferta;
                $descuento->cantidad    = $cantidadOferta;
                
                if($descuento->save()) {
                    $respuesta = true;
                }else{
                    $respuesta = false;
                }
            }else{
                $respuesta = false;
            }
            

            DB::commit();

            $rpta = array(
                'response'          =>  $respuesta,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }

    public function descuentoEditar(Request $request)
    {
        DB::beginTransaction();
        try {
            
            $descuento = descuentosProductos::find($request['idEditar']);
            $descuento->producto_id = $request['codigoProductoEditar'];
            $descuento->porcentaje  = $request['porcentajeEditar'];
            $descuento->cantidad    = $request['cantidadEditar'];

            if($descuento->update()) {
                $control = new control;
                $control->user_id = auth()->id();
                $control->metodo = "Editar";
                $control->tabla = "descuentosProductos";
                $control->campos = "all";
                $control->datos = $request['idEditar'].', '. $request['codigoProductoEditar'].', '. $request['porcentajeEditar'].', '. $request['cantidadDescuentos'];
                $control->descripcion = "Editar un descuento";
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

    public function descuentoEliminar(Request $request)
    {

        $idDescuento = $request['idDescuento'];

        DB::beginTransaction();
        try {
            
            $descuento = descuentosProductos::find($request['idDescuento']);

            if($descuento->delete()) {
                $respuesta = true;
                $mensaje   = "OFERTA ELIMINADA CORRECTAMENTE";
            }else{
                $respuesta = true;
                $mensaje   = "HUBO UN ERROR AL ELIMINAR LA OFERTA"; 
            }

            DB::commit();

            $rpta = array(
                'respuesta' => true,
                'mensaje'   => $mensaje
            );
            
            return json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            return json_encode($e->getMessage());
        }
    }


}
