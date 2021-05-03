<?php

namespace App\Http\Controllers\panel\movimientos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReporteExport;

use App\productos;
use App\almacenes;
use App\transferencias;
use App\entradas;
use App\ventas;
use App\sucursales;
use App\productosEntradas;
use App\registrosCajas;
use App\cajasProductos;
use App\notasCreditos;
use App\detallesVentas;
use App\usuariosSucursales;

class movimientosController extends Controller
{
    public function reporte(Request $request)
    {
        //$sucursales = $request->sucursales;
        ////MOMENTANEO////
        // $id_user = auth()->id();
        $id_user = $request->header('usuid');
        $sucursales_id = usuariosSucursales::where('user_id', $id_user)->get('sucursal_id');
        $sucursales = [];
        foreach ($sucursales_id as $id) {
            $sucursales[] = $id->sucursal_id;
        }
        ////

    	$producto = productos::where('codigo', $request->codigo)->first(['id', 'nombre']);

        if (!$producto) {
            return $answer = ['response' => false, 'mensaje' => "Producto no encontrado"];
        }
          //// PRIMERA PARTE - DATOS PRINCIPALES DEL PRODUCTO ////
          ////////////////// ENTRADAS - TOTAL & FILTRO DE ENTRADAS POR SUCURSAL ///////////////////////////
          $entradas_filtro = entradas::where(function ($query) use($sucursales) {
                                              foreach ($sucursales as $sucursal) {
                                                  $query->orWhere('sucursal_id', $sucursal);
                                              }
                                          })
                                          ->get([
                                              'id',
                                              'sucursal_id',
                                              'factura'
                                          ]);

          $entradas_totales = productosEntradas::where(function ($query) use($entradas_filtro, $producto) {
                                                      foreach ($entradas_filtro as $entrada) {
                                                          $query->orWhere([
                                                              ['producto_id', $producto->id],
                                                              ['entrada_id', $entrada->id]
                                                          ]);
                                                      }
                                                  })
                                                  ->sum('cantidad');

          //////////////// TRANSFERENCIAS TOTALES /////////////////////////////////////
          $transferencias_enviadas = transferencias::where(function ($query) use($sucursales, $producto) {
                                                          foreach ($sucursales as $sucursal) {
                                                              $query->orWhere([
                                                                  ['origen', $sucursal],
                                                                  ['producto_id', $producto->id]
                                                              ]);
                                                          }
                                                      })
                                                      ->sum('cantidad');

          $transferencias_recibidas = transferencias::where(function ($query) use($sucursales, $producto) {
                                                          foreach ($sucursales as $sucursal) {
                                                              $query->orWhere([
                                                                  ['destino', $sucursal],
                                                                  ['producto_id', $producto->id]
                                                              ]);
                                                          }
                                                      })
                                                      ->sum('cantidad');

          ////////// VENTAS - FILTRO POR SUCURSAL(ES) & VENTAS TOTALES /////////////////////////////
          $ventas_filtro = ventas::where(function ($query) use($sucursales) {
                                          foreach ($sucursales as $sucursal) {
                                              $query->orWhere('sucursal_id', $sucursal);
                                          }
                                      })
                                      ->get('id');

          $ventas_totales = detallesVentas::where(function ($query) use($ventas_filtro, $producto) {
                                                  foreach ($ventas_filtro as $ventas) {
                                                      $query->orWhere([
                                                          ['venta_id', $ventas->id],
                                                          ['producto_id', $producto->id]
                                                      ]);
                                                  }
                                              })
                                              ->sum('cantidad');

          ///////////// STOCK ACTUAL SEGUN SUCURSAL(ES) //////////////////////////////////
          $stock_actual = almacenes::where(function ($query) use($sucursales, $producto) {
                                          foreach ($sucursales as $sucursal) {
                                              $query->orWhere([
                                                  ['sucursal_id', $sucursal],
                                                  ['producto_id', $producto->id]
                                              ]);
                                          }
                                      })
                                      ->sum('stock');

          //// AGREGACION DEL TOTAL DE LOS DATOS A LA INFORMACION INICIAL DEL PRODUCTO ///////
          $producto['response'] = true;
          $producto['entradasTotales'] = $entradas_totales;
          $producto['transferEnviadas'] = $transferencias_enviadas;
          $producto['transferRecibidas'] = $transferencias_recibidas;
          $producto['ventasTotales'] = $ventas_totales;
          $producto['stockActual'] = $stock_actual;


          //// SEGUNDA PARTE - REPORTE DE MOVIMIENTOS ////
          //////////////////////// ENTRADAS DEL PRODUCTO ////////////////////////////////////
          $array_global = [];




        if (count($entradas_filtro) > 0) {
            foreach ($entradas_filtro as $entrada) {
                $sucursal_nombre = sucursales::find($entrada->sucursal_id, ['nombre']);

                $productosEntradas = productosEntradas::where([
                                                            ['producto_id', $producto->id],
                                                            ['entrada_id', $entrada->id]
                                                        ])
                                                        ->get([
                                                            'created_at as fecha',
                                                            'cantidad'
                                                        ]);

                if (count($productosEntradas) == 1) {
                    $entradas = [];

                    $entradas['sucursal'] = $sucursal_nombre->nombre;
                    $entradas['fecha'] = $productosEntradas[0]['fecha'];
                    $entradas['accion'] = "Entrada";
                    $entradas['registro'] = $entrada->factura;
                    $entradas['cantidad'] = $productosEntradas[0]['cantidad'];
                    $entradas['operacion'] = 1;

                    $array_global[] = $entradas;
                }
            }
        }

        ////////////////////////// TRANSFERENCIAS DEL PRODUCTO /////////////////////////////////////
        $transferencias_tabla = transferencias::where(function ($query) use($sucursales, $producto) {
                                                        foreach ($sucursales as $sucursal) {
                                                            $query->orWhere([
                                                                        ['origen', $sucursal],
                                                                        ['producto_id', $producto->id]
                                                                    ])
                                                                  ->orWhere([
                                                                        ['destino', $sucursal],
                                                                        ['producto_id', $producto->id]
                                                                    ]);
                                                        }
                                                    })
                                                    ->get([
                                                        'origen',
                                                        'destino',
                                                        'producto_id',
                                                        'cantidad',
                                                        'created_at'
                                                    ]);

        if (count($transferencias_tabla) > 0) {
            foreach ($transferencias_tabla as $transferencia) {
                $transferencias = [];

                $sucursal_nombre = sucursales::find($transferencia->origen, ['nombre']);

                $transferencias['sucursal'] = $sucursal_nombre->nombre;
                $transferencias['fecha'] = $transferencia->created_at->format('Y-m-d H:i:s');
                $transferencias['accion'] = "Transferencia";
                $transferencias['registro'] = "";

                if (in_array($transferencia->origen, $sucursales))  {
                    $transferencias['cantidad'] = $transferencia->cantidad;
                    $transferencias['operacion'] = 0;
                } else {
                    $transferencias['cantidad'] = $transferencia->cantidad;
                    $transferencias['operacion'] = 1;
                }

                $array_global[] = $transferencias;
            }
        }

        ////////////////////////////// VENTAS DEL PRODUCTO /////////////////////////////////////
        $ventas_tabla = detallesVentas::where(function ($query) use($ventas_filtro, $producto) {
                                            foreach ($ventas_filtro as $ventas) {
                                                $query->orWhere([
                                                    ['venta_id', $ventas->id],
                                                    ['producto_id', $producto->id]
                                                ]);
                                            }
                                        })
                                        ->get([
                                            'venta_id',
                                            'cantidad',
                                            'created_at'
                                        ]);

        if (count($ventas_tabla) > 0) {
            foreach ($ventas_tabla as $venta) {
                $ventas = [];

                $sucursal_id = ventas::find($venta->venta_id, ['sucursal_id', 'numero']);
                $sucursal_nombre = sucursales::find($sucursal_id->sucursal_id, ['nombre']);

                $ventas['sucursal'] = $sucursal_nombre->nombre;
                $ventas['fecha'] = $venta->created_at->format('Y-m-d H:i:s');
                $ventas['accion'] = "Venta";
                $ventas['registro'] = $sucursal_id->numero;
                $ventas['cantidad'] = $venta->cantidad;
                $ventas['operacion'] = 0;

                $array_global[] = $ventas;
            }
        }

        //////// CAJAS ABIERTAS O CERRADAS QUE AFECTARON EL STOCK DEL PRODUCTO //////////////
        $tipo_producto = productos::find($producto->id, ['tipoCajaProducto_id as tipo']);

        $caja = cajasProductos::where('producto_id', $producto->id)
                                    ->first('cajaProducto_id as id');

        $cantidad_caja = cajasProductos::where('producto_id', $producto->id)
                                                ->first('cantidad');

        $registrosCajas_tabla = registrosCajas::where(function ($query) use($sucursales, $producto, $tipo_producto, $caja) {
                                                    foreach ($sucursales as $sucursal) {
                                                        if ($tipo_producto->tipo == 3) {
                                                            $query->orWhere([
                                                                ['sucursal_id', $sucursal],
                                                                ['cajaProducto_id', $producto->id]
                                                            ]);
                                                        } else if ($caja) {
                                                            $query->orWhere([
                                                                ['sucursal_id', $sucursal],
                                                                ['cajaProducto_id', $caja->id]
                                                            ]);
                                                        } else {
                                                            $query->orWhere([
                                                                ['sucursal_id', $sucursal],
                                                                ['cajaProducto_id', 0]
                                                            ]);
                                                        }
                                                    }
                                                })
                                                ->get([
                                                    'sucursal_id',
                                                    'cantidad',
                                                    'accion',
                                                    'created_at'
                                                ]);

        if (count($registrosCajas_tabla) > 0) {
            foreach ($registrosCajas_tabla as $registro) {
                $registroCaja = [];

                $sucursal_nombre = sucursales::find($registro->sucursal_id, ['nombre']);

                $registroCaja['sucursal'] = $sucursal_nombre->nombre;
                $registroCaja['fecha'] = $registro->created_at->format('Y-m-d H:i:s');

                if ($tipo_producto->tipo == 3) {
                    if ($registro->accion == 'ABRIR') {
                        $registroCaja['accion'] = "Caja Abierta";
                        $registroCaja['registro'] = "";
                        $registroCaja['cantidad'] = $registro->cantidad;
                        $registroCaja['operacion'] = 0;
                    } else {
                        $registroCaja['accion'] = "Caja Cerrada";
                        $registroCaja['registro'] = "";
                        $registroCaja['cantidad'] = $registro->cantidad;
                        $registroCaja['operacion'] = 1;
                    }
                } else {
                    if ($registro->accion == 'ABRIR') {
                        $registroCaja['accion'] = "Caja Abierta";
                        $registroCaja['registro'] = "";
                        $registroCaja['cantidad'] = $registro['cantidad'] * $cantidad_caja['cantidad'];
                        $registroCaja['operacion'] = 1;
                    } else {
                        $registroCaja['accion'] = "Caja Cerrada";
                        $registroCaja['registro'] = "";
                        $registroCaja['cantidad'] = $registro['cantidad'] * $cantidad_caja['cantidad'];
                        $registroCaja['operacion'] = 0;
                    }
                }

                $array_global[] = $registroCaja;
            }
        }

        /////////////// VENTAS CANCELADAS DEL PRODUCTO(NOTAS CREDITOS) ///////////////////////////
        $notasCreditos_filtro = notasCreditos::where(function ($query) use($ventas_tabla) {
                                                    if (count($ventas_tabla) > 0) {
                                                        foreach ($ventas_tabla as $venta) {
                                                            $query->orWhere('venta_id', $venta->venta_id);
                                                        }
                                                    } else {
                                                        $query->where('venta_id', 0);
                                                    }
                                                })
                                                ->get([
                                                    'venta_id',
                                                    'created_at'
                                                ]);

            $notasCreditos_totales = detallesVentas::where(function ($query) use($notasCreditos_filtro) {
                                            if (count($notasCreditos_filtro) > 0) {
                                                foreach ($notasCreditos_filtro as $notaCredito) {
                                                    $query->orWhere('venta_id', $notaCredito->venta_id);
                                                }
                                            } else {
                                                $query->where('venta_id', 0);
                                            }
                                        })
                                        ->sum('cantidad');

            $producto['ventasCanceladas'] = $notasCreditos_totales;

        if (count($notasCreditos_filtro) > 0) {
            foreach ($notasCreditos_filtro as $notaCredito) {
                $notasCreditos = [];

                $sucursal_id = ventas::find($notaCredito->venta_id, ['sucursal_id']);
                $sucursal_nombre = sucursales::find($sucursal_id->sucursal_id, ['nombre']);
                $cantidad = detallesVentas::where([
                                                ['venta_id', $notaCredito->venta_id],
                                                ['producto_id', $producto->id]
                                            ])
                                            ->sum('cantidad');

                $notasCreditos['sucursal'] = $sucursal_nombre->nombre;
                $notasCreditos['fecha'] = $notaCredito->created_at->format('Y-m-d H:i:s');
                $notasCreditos['accion'] = "Venta cancelada";
                $notasCreditos['registro'] = "";
                $notasCreditos['cantidad'] = $cantidad;
                $notasCreditos['operacion'] = 1;

                $array_global[] = $notasCreditos;
            }
        }

        /////////////// REORDENAMIENTO SEGUN FECHA ////////////////////////
        usort($array_global, function ($a, $b) {
                    return strtotime($a['fecha']) > strtotime($b['fecha']);
                });

        //////////////// OBTENCION DE COLUMNA DE STOCK ACTUAL ///////////////////////////////
        $nueva_cantidad = 0;

        for ($i=0; $i < count($array_global); $i++) {
            if ($array_global[$i]['operacion'] == 0) {
                $array_global[$i]['stock'] = $nueva_cantidad - $array_global[$i]['cantidad'];
            } else {
                $array_global[$i]['stock'] = $nueva_cantidad + $array_global[$i]['cantidad'];
            }

            $nueva_cantidad = $array_global[$i]['stock'];
        }

        //// GENERAR EXCEL ////
        if ($request->descargar) {
            return Excel::download(
                new ReporteExport($array_global),$producto->nombre.'-'.$request->codigo.'.xlsx'
            );
        }

        // AGREGACION DE LOS REPORTES DE MOVIMIENTOS //
        $producto['movimientos'] = $array_global;

        return $producto;
    }
}
