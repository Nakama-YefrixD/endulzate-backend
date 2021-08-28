<?php

namespace App\Http\Controllers\panel\transferencia;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\transferencias;
use App\usuariosSucursales;
use App\productos;
use App\almacenes;
use App\cajasVentas;

use DB;

class transferenciaController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        return view('react.app');
    }

    public function tb_transferencias(Request $request)
    {

        // $idUsuario  =   $request['idUsuario']; 
        // $idUsuario  = $request->header('usuid');
        $idUsuario  = $request->header('usuid');
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

        
        
        $transferencias = transferencias::join('sucursales as s', 'transferencias.origen', 's.id')
                                        ->join('sucursales as ss', 'transferencias.destino', 'ss.id')
                                        ->join('productos as p', 'transferencias.producto_id', 'p.id')
                                        ->where(function ($query) use( $sucursalesUsuario , $idSucursal, $sucursalusuarioPredeterminado ) {
                                            if($idSucursal){
                                                if($idSucursal == 0){
                                                    if(sizeof($sucursalesUsuario) > 0 ){
                                                        foreach($sucursalesUsuario as $sucursalUsuario){
                                                            $query->orwhere('transferencias.origen',  $sucursalUsuario->sucursal_id);
                                                            $query->orwhere('transferencias.destino', $sucursalUsuario->sucursal_id);
                                                            
                                                        }
                                                    }else{
                                                        $query->orwhere('transferencias.origen',  0);
                                                        $query->orwhere('transferencias.destino', 0);
                                                    } 
                                                }else{
                                                    $query->orwhere('transferencias.origen',  $idSucursal);
                                                    $query->orwhere('transferencias.destino', $idSucursal);
                                                }

                                            }else{
                                                $query->orwhere('transferencias.origen', $sucursalusuarioPredeterminado);
                                                $query->orwhere('transferencias.destino', $sucursalusuarioPredeterminado);
                                            }
                                        })
                                        ->orderby('transferencias.id', 'desc')
                                        ->paginate(10, array(
                                            'transferencias.id as idTransferencia',
                                            's.nombre  as origenTransferencia',
                                            'ss.nombre as destinoTransferencia',
                                            'p.codigo  as codigoProducto',
                                            'p.nombre  as nombreProducto',
                                            'transferencias.cantidad as cantidadTransferida'
                                            

                                        ));

        $rpta = array(
            'respuesta'          => true,
            'tb_transferencias'  => $transferencias
        );
        
        return json_encode($rpta);

    }

    public function crear(Request $request)
    {   
        date_default_timezone_set("America/Lima");
        $idOrigen               = $request['idOrigen'];
        $idDestino              = $request['idDestino'];
        $idProducto             = $request['idProducto'];
        $codigoProducto         = $request['codigoProducto'];
        $cajaVentaOrigen_id     = $request['idCajaVentaOrigen'];
        $cajaVentaDestino_id    = $request['idCajaVentaDestino'];
        $userOrigen_id          = $request['idUsuarioOrigen'];
        $userDestino_id         = $request['idUsuarioDestino'];
        $cantidad               = $request['cantidad'];
        $motivo                 = $request['motivo'];
        $estado                 = 0; // EL ESTADO SIEMPRE SERA 0
        $antesOrigenCantidad    = $request['stockAntesOrigen'];
        $despuesOrigenCantidad  = $request['stockDespuesOrigen'];
        $antesDestinoCantidad   = $request['stockAntesDestino'];
        $despuesDestinoCantidad = $request['stockDespuesDestino'];

        DB::beginTransaction();
        try {

            // COMPROBAR SI EL ID DEL PRODUCTO SE ENVIO O NO.
            if($idProducto == null){
                $producto = productos::where('codigo', $codigoProducto)
                                        ->first([
                                            'id'
                                        ]);
                if($producto){
                    $idProducto             = $producto->id;
                    $almacenOrigen = almacenes::where('producto_id', $idProducto)
                                                ->where('sucursal_id', $idOrigen)
                                                ->first([
                                                    'id',
                                                    'stock',
                                                    'total'
                                                ]);
                    if($almacenOrigen){
                        $antesOrigenCantidad    = $almacenOrigen->stock;
                        $despuesOrigenCantidad  = $almacenOrigen->stock + $cantidad;
                    }else{
                        $rpta = array(
                            'respuesta' => false,
                            'mensaje'   => 'EL PRODUCTO NO EXISTE EN EL ALMACEN DE ORIGEN'
                        );
                        
                        return json_encode($rpta);
                    }
                    

                }else{
                    $rpta = array(
                        'respuesta' => false,
                        'mensaje'   => 'EL PRODUCTO NO EXISTE'
                    );
                    
                    return json_encode($rpta);
                }
            }

            if($cajaVentaOrigen_id == null){

                $cajaVenta    = cajasVentas::where('sucursal_id', $idOrigen)
                                            ->where('cierre', null)
                                            ->first();

                // QUITAR CUANDO FUNCIONE LA CAJA DE VENTA
                if($cajaVenta){
                    $cajaVentaOrigen_id = $cajaVenta->id;
                }else{
                    $cajaVentaOrigen_id = null;
                }

            }

            if($cajaVentaDestino_id == null){

                $cajaVenta    = cajasVentas::where('sucursal_id', $idDestino)
                                            ->where('cierre', null)
                                            ->first();

                // QUITAR CUANDO FUNCIONE LA CAJA DE VENTA
                if($cajaVenta){
                    $cajaVentaDestino_id = $cajaVenta->id;
                }else{
                    $cajaVentaDestino_id = null;
                }

            }

            if($userOrigen_id == null){
                // $userOrigen_id = $request->header('usuid');
                $userOrigen_id = $request->header('usuid');
            }

            

            $almacenDestino = almacenes::where('producto_id', $idProducto)
                                    ->where('sucursal_id', $idDestino)
                                    ->first([
                                        'id',
                                        'stock',
                                        'total'
                                    ]);
            if($almacenDestino){

                $antesDestinoCantidad   = $almacenDestino->stock;
                $despuesDestinoCantidad = $almacenDestino->stock + $cantidad;
                
            }else{
                $rpta = array(
                    'respuesta'         => false,
                    'mensaje'           => 'EL PRODUCTO NO EXISTE EN EL ALMACEN DEL DESTINO',
                    'almacenDestino'    => $almacenDestino
                );
                
                return json_encode($rpta);
            }

            $transferencia = new transferencias;
            $transferencia->origen                  = $idOrigen;
            $transferencia->destino                 = $idDestino;
            $transferencia->producto_id             = $idProducto;
            $transferencia->cajaVentaOrigen_id      = $cajaVentaOrigen_id;
            $transferencia->cajaVentaDestino_id     = null;
            $transferencia->userOrigen_id           = $userOrigen_id;
            $transferencia->userDestino_id          = null;
            $transferencia->cantidad                = $cantidad;
            $transferencia->motivo                  = $motivo;
            $transferencia->estado                  = $estado;
            $transferencia->antesOrigenCantidad     = $antesOrigenCantidad;
            $transferencia->antesDestinoCantidad    = $antesDestinoCantidad;
            $transferencia->despuesOrigenCantidad   = $despuesOrigenCantidad;
            $transferencia->despuesDestinoCantidad  = $despuesDestinoCantidad;
            $transferencia->fechaEnvio              = date("Y-m-d H:i:s");
            $transferencia->fechaRecibido           = null;
            if($transferencia->save()){

                $almacenDestino->stock = $almacenDestino->stock + $transferencia->cantidad;
                $almacenDestino->total = $almacenDestino->total + $transferencia->cantidad;
                $almacenDestino->transferenciarecibida = $almacenDestino->transferenciarecibida + $transferencia->cantidad;

                if($almacenDestino->update()){
                    $almacenOrigen = almacenes::where('producto_id', $idProducto)
                                    ->where('sucursal_id', $idOrigen)
                                    ->first([
                                        'id',
                                        'stock',
                                        'total'
                                    ]);
                    if($almacenOrigen){

                        $almacenOrigen->stock = $almacenOrigen->stock - $transferencia->cantidad;
                        $almacenOrigen->transferenciarealizada = $almacenOrigen->transferenciarealizada - $transferencia->cantidad;
                        
                        if($almacenOrigen->update()){
                            DB::commit();
                            $rpta = array(
                                'respuesta' => true,
                                'mensaje'   => 'LA TRANSFERENCIA SE COMPLETO CORRECTAMENTE'
                            );
                            return json_encode($rpta);
                        }else{
                            $rpta = array(
                                'respuesta' => false,
                                'mensaje'   => 'EL STOCK DE ORIGEN NO SE PUDO ACTUALIZAR'
                            );
                            
                            return json_encode($rpta);
                        }
                        
                    }else{
                        $rpta = array(
                            'respuesta' => false,
                            'mensaje'   => 'EL PRODUCTO NO EXISTE EN EL ALMACEN DE ORIGEN'
                        );
                        
                        return json_encode($rpta);
                    }
                }else{
                    $rpta = array(
                        'respuesta' => false,
                        'mensaje'   => 'EL STOCK DE DESTINO NO SE PUDO ACTUALIZAR'
                    );
                    
                    return json_encode($rpta);
                }

            }else{
                $rpta = array(
                    'respuesta' => false,
                    'mensaje'   => 'NO SE PUDO GUARDAR LA TRANSFERENCIA'
                );
                
                return json_encode($rpta);
            }
            
        }catch (\Exception $e) {
            DB::rollBack();
            $rpta = array(
                'respuesta' => false,
                'mensaje'   => $e->getMessage()
            );
            
            return json_encode($rpta);
            
        }
    }

    public function eliminar(Request $request)
    {
        // $idUsuario  =   $request['idUsuario']; 
        // $idUsuario          = $request->header('usuid');
        $idUsuario          = $request->header('usuid');
        $idTransferencia    = $request['idTransferencia'];

        DB::beginTransaction();
        try {
            $transferencia = transferencias::find($idTransferencia);
            $sucursalesUsuario  = usuariosSucursales::where('user_id', $idUsuario)
                                                    ->where('sucursal_id', $transferencia->origen )
                                                    ->first([
                                                        'sucursal_id'
                                                    ]);
            if($sucursalesUsuario){

                $almacenOrigen = almacenes::where('producto_id', $transferencia->producto_id)
                                            ->where('sucursal_id',   $transferencia->origen)
                                            ->first([
                                                'id',
                                                'stock',
                                                'total'
                                            ]);

                if($almacenOrigen){
                    $almacenOrigen->stock = $almacenOrigen->stock + $transferencia->cantidad;
                    if($almacenOrigen->update()){

                        $almacenDestino = almacenes::where('producto_id',  $transferencia->producto_id)
                                        ->where('sucursal_id', $transferencia->destino)
                                        ->first([
                                            'id',
                                            'stock',
                                            'total'
                                        ]);
                                            
                        if($almacenDestino){
                            $almacenDestino->stock = $almacenDestino->stock - $transferencia->cantidad;
                            $almacenDestino->total = $almacenDestino->total - $transferencia->cantidad;
                            if($almacenDestino->update()){
                                
                                if($transferencia->delete()){
                                    DB::commit();
                                    $rpta = array(
                                        'respuesta' => true,
                                        'mensaje'   => 'LA TRANSFERENCIA SE ELIMINO CORRECTAMENTE'
                                    );
                                    return json_encode($rpta);
                                }else{
                                    $rpta = array(
                                        'respuesta' => false,
                                        'mensaje'   => 'LA TRANSFERENCIA NO SE PUDO ELIMINAR'
                                    );
                                    
                                    return json_encode($rpta);
                                }

                                
                            }else{
                                $rpta = array(
                                    'respuesta' => false,
                                    'mensaje'   => 'EL STOCK DE DESTINO NO SE PUDO ACTUALIZAR'
                                );
                                
                                return json_encode($rpta);
                            }
                        }else{
                            $rpta = array(
                                'respuesta' => false,
                                'mensaje'   => 'EL ALMACEN DE DESTINO NO EXISTE'
                            );
                            
                            return json_encode($rpta);
                        }

                    }else{
                        $rpta = array(
                            'respuesta' => false,
                            'mensaje'   => 'EL STOCK DE ORIGEN NO SE PUDO ACTUALIZAR'
                        );
                        
                        return json_encode($rpta);
                    }
                }else{
                    $rpta = array(
                        'respuesta' => false,
                        'mensaje'   => 'EL ALMACEN DEL ORIGEN NO EXISTE'
                    );
                    
                    return json_encode($rpta);
                }


            }else{
                $rpta = array(
                    'respuesta' => false,
                    'mensaje'   => 'NO TIENES PERMISO PARA ELIMINAR ESTA TRANSFERENCIA'
                );
                return json_encode($rpta);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            $rpta = array(
                'respuesta' => false,
                'mensaje'   => $e->getMessage()
            );
            
            return json_encode($rpta);
            
        }
    }


}
