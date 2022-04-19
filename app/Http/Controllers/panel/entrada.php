<?php

namespace App\Http\Controllers\panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\entradas;
use App\proveedores;
use App\productos;
use App\sucursales;
use Yajra\DataTables\DataTables;
use App\productosEntradas;
use App\almacenes;

use PDF;

class entrada extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        $proveedores = Proveedores::all();
        $productos = Productos::all();
        $data = array(
            'proveedores' => $proveedores,
            'productos' => $productos,
        );

        // return view('almacen.entradas')->with($data);
        return view('react.app');
    }

    public function tb_entradas(Request $request)
    {
        $idUsuario = $request->header('usuid');

        if ($idUsuario == 1 || $idUsuario == 2) {
            $idSucursal = 1;
        } else {
            $idSucursal = 2;
        }

        return productosEntradas::join('entradas', 'productosEntradas.entrada_id', '=', 'entradas.id')
                            ->join('productos', 'productosEntradas.producto_id', '=', 'productos.id')
                            ->where('entradas.sucursal_id', $idSucursal)
                            ->where(function ($query) use($request) {
                                if($request->buscar != '') {
                                    $query->where('entradas.fecha', 'like', '%' . $request->buscar . '%')
                                        ->orWhere('productos.codigo', 'like', '%' . $request->buscar . '%')
                                        ->orWhere('productos.nombre', 'like', '%' . $request->buscar . '%');
                                }
                            })
                            ->orderBy('entradas.id', 'desc')
                            ->select(
                                'entradas.id as entradaId',
                                'entradas.fecha',
                                'entradas.factura',
                                'productos.codigo',
                                'productos.nombre',
                                'productosEntradas.id',
                                'productosEntradas.cantidad',
                                'productosEntradas.precio'
                            )->paginate(10);
    }

    public function verDetalleEntrada($idEntrada)
    {
        $entrada   = entradas::find($idEntrada);
        $proveedor = proveedores::find($entrada->proveedor_id);
        $sucursal  = sucursales::find($entrada->sucursal_id);

        $productos_datos = productosEntradas::where('entrada_id', $idEntrada)
                                                ->get([
                                                    'producto_id',
                                                    'precio', 
                                                    'cantidad'
                                                ]);

        $cantidad_total  = productosEntradas::where('entrada_id', $idEntrada)
                                                ->sum('cantidad');

        $productos = [];
        $importeT  = 0;

        foreach ($productos_datos as $producto) {

            $producto_dato = productos::find($producto->producto_id);

            $dato = [
                'nombre'   => $producto_dato->nombre,
                'cantidad' => $producto->cantidad,
                'precio'   => $producto->precio,
                'importe'  => $producto->cantidad * $producto->precio
            ];

            $importeT += $dato['importe']; 
            $productos[] = $dato;
        }

        $datos = [
            'item'      => 1,
            'entrada'   => $entrada,
            'proveedor' => $proveedor,
            'sucursal'  => $sucursal,
            'productos' => $productos,
            'cantidadT' => $cantidad_total,
            'importeT'  => $importeT
        ];

        $pdf = PDF::loadView('almacen.loadsEntradas.detallesEntradaPDF', $datos)->setPaper('a4');
        return $pdf->stream();
    }

    public function edit(Request $request)
    {
        try {

            $productoEntrada = productosEntradas::find($request->id);
            $productoEntrada->precio = $request->precio;
            $productoEntrada->save();
            
            return $answer = ['response' => true];

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function destroy(Request $request)
    {


        try {
        $entrada = entradas::find($request->id);

        if($entrada){
            
            $productosEntradas = productosEntradas::join('productos as pro', 'pro.id', 'productosEntradas.producto_id')
                                        ->where('productosEntradas.entrada_id', $request->id)
                                        ->where('pro.codigo', $request->codigo)
                                        ->where('productosEntradas.cantidad', $request->cantidad)
                                        ->get([
                                            'productosEntradas.id',
                                            'pro.id as proid',
                                            'productosEntradas.cantidad'
                                        ]);

            foreach($productosEntradas as $productoEntrada){

                $almacen = almacenes::where('sucursal_id', $entrada->sucursal_id)
                                    ->where('producto_id', $productoEntrada->proid)
                                    ->first();

                if($almacen){
                    $almacen->stock = doubleval($almacen->stock) - doubleval($productoEntrada->cantidad);
                    $almacen->total = doubleval($almacen->total) - doubleval($productoEntrada->cantidad);
                    if($almacen->update()){
                        $producto = productos::find($productoEntrada->proid);
                        
                        if($producto){

                            $producto->cantidad = doubleval($producto->cantidad) - doubleval($productoEntrada->cantidad);
                            $producto->total = doubleval($producto->total) - doubleval($productoEntrada->cantidad);
                            if($producto->update()){
                                productosEntradas::where('id', $productoEntrada->id)->delete();
                            }

                        }else{

                        }
                    }
                }

            }

            $productosEntradas = productosEntradas::join('productos as pro', 'pro.id', 'productosEntradas.producto_id')
                                        ->where('productosEntradas.entrada_id', $request->id)
                                        ->where('pro.codigo', $request->codigo)
                                        ->where('productosEntradas.cantidad', $request->cantidad)
                                        ->get([
                                            'productosEntradas.id',
                                            'pro.id as proid',
                                            'productosEntradas.cantidad'
                                        ]);

            if(sizeof($productosEntradas) > 0){

            }else{
                entradas::destroy($request->id);
            }


        }else{

        }


        

        // $producto = productos::where('codigo', $request->codigo)->first('id');
        // $cantidad = productos::join('almacenes', 'productos.id', '=', 'almacenes.producto_id')
        //                         ->where([
        //                             ['productos.id', $producto->id],
        //                             ['almacenes.producto_id', $producto->id]
        //                         ])
        //                         ->first([
        //                             'productos.cantidad as pStock',
        //                             'productos.total    as pTotal',
        //                             'almacenes.stock    as aStock',
        //                             'almacenes.total    as aTotal'
        //                         ]);

        // $condicion = productosEntradas::where('entrada_id', $request->id)->get('producto_id');

        // productos::find($producto->id)
        //             ->update([
        //                 'cantidad' => $cantidad->pStock - $request->cantidad,
        //                 'total'    => $cantidad->pTotal - $request->cantidad
        //             ]);

        // almacenes::where('producto_id', $producto->id)
        //             ->update([
        //                 'stock' => $cantidad->aStock - $request->cantidad,
        //                 'total' => $cantidad->aTotal - $request->cantidad
        //             ]);

        // productosEntradas::where([
        //                     ['producto_id', $producto->id],
        //                     ['entrada_id', $request->id]
        //                 ])
        //                 ->delete();

        // if (count($condicion) == 1) {
        //     entradas::destroy($request->id);
        // }

        $rpta = array(
              'response'      =>  true,
        );

        echo json_encode($rpta);
      } catch (\Exception $e) {

          //echo $datosProducto;
          echo json_encode($e->getMessage());

      }
    }
}
