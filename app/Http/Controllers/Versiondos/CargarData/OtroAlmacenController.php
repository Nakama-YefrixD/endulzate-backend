<?php

namespace App\Http\Controllers\Versiondos\CargarData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\User;
use App\marcas;
use App\Tipos;
use App\productos;
use App\sucursales;
use App\almacenes;

class OtroAlmacenController extends Controller
{
    public function cargar(Request $request)
    {
        $respuesta      = true;
        $mensaje        = "";
        $datos          = [];
        $mensajeDetalle = "";
        $mensajedev     = "";

        date_default_timezone_set("America/Lima");
        $fechaActual = date('Y-m-d');

        $logs = array(
            "NUMERO_LINEAS_EXCEL" => 0,
            "MARCAS_SELECCIONADAS" => [],
            "MARCAS_AGREGADAS"     => [],
            "MARCAS_NO_AGREGADAS"  => [],
            "PRODUCTO_NO_ACTUALIZADO"  => [],
            "PRODUCTO_NO_CREADO"  => [],
            "ARCHIVO_NO_CARGADO" => ""
        );

        try{
            $codigoArchivoAleatorio = mt_rand(0, mt_getrandmax())/mt_getrandmax();

            $usutoken = $request->header('api-token');
            $usuid = $request->header('usuid');
            $archivo  = $_FILES['file']['name'];


            $fichero_subido = base_path().'/public/versiondos/excels/almacen/'.basename($codigoArchivoAleatorio.'-1-admin-'.$fechaActual.'-'.$_FILES['file']['name']);
            if (move_uploaded_file($_FILES['file']['tmp_name'], $fichero_subido)) {

                $objPHPExcel    = IOFactory::load($fichero_subido);
                $objPHPExcel->setActiveSheetIndex(0);
                $numRows        = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
                $ultimaColumna  = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();

                $logs['NUMERO_LINEAS_EXCEL'] = $numRows;

                for ($i=2; $i <= $numRows ; $i++) {
                    $sucursalExcel       = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
                    $tipoProducto        = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
                    $marcaProducto       = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
                    $codigoProducto      = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue();
                    $descripcionProducto = $objPHPExcel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue();
                    $precioCompra        = $objPHPExcel->getActiveSheet()->getCell('F'.$i)->getCalculatedValue();
                    $precioVenta         = $objPHPExcel->getActiveSheet()->getCell('G'.$i)->getCalculatedValue();
                    $stock               = $objPHPExcel->getActiveSheet()->getCell('H'.$i)->getCalculatedValue();
                    $caja                = $objPHPExcel->getActiveSheet()->getCell('I'.$i)->getCalculatedValue();

                    // if($i == 2){
                    //     Productos::update([])
                    // }

                    $marca = marcas::where('nombre', $marcaProducto)->first();
                    $idMarca = 0;

                    if($marca){
                        $idMarca = $marca->id;
                        $logs['MARCAS_SELECCIONADAS'][] = $marcaProducto." EN LINEA: ".$i;
                    }else{
                        $marcaNueva = new marcas;
                        $marcaNueva->nombre = $marcaProducto;

                        if($marcaNueva->save()) {
                            $idMarca = $marcaNueva->id;
                            $logs['MARCAS_AGREGADAS'][] = $marcaProducto." EN LINEA: ".$i;
                        }else{
                            $logs['MARCAS_NO_AGREGADAS'][] = $marcaProducto." EN LINEA: ".$i;
                        }
                    }

                    $tipo = Tipos::where('nombre', $tipoProducto)->first();
                    $idTipo = 0;

                    if($tipo){
                        $idTipo = $tipo->id;
                        $logs['TIPOS_ASIGNADOS'][] = $tipoProducto." EN LINEA: ".$i;
                    }else{
                        $tipoNuevo = new Tipos;
                        $tipoNuevo->nombre = $tipoProducto;

                        if($tipoNuevo->save()) {
                            $logs['TIPOS_AGREGADOS'][] = $tipoProducto." EN LINEA: ".$i;
                        }else{
                            $logs['TIPOS_NO_AGREGADAS'][] = $tipoProducto." EN LINEA: ".$i;
                        }
                    }

                    

                    
                    $producto = productos::where('codigo', $codigoProducto)->fist();

                    if($producto){
                        $producto->marca_id    = $idMarca;
                        $producto->tipo_id     = $idTipo;
                        $producto->nombre      = $descripcionProducto;
                        $producto->cantidad    = $producto->cantidad + $stock;
                        $producto->total       = $producto->cantidad + $stock;
                        $producto->vendido     = 0;
                        $producto->precio      = $precioVenta;
                        if($caja == "CAJA"){
                            $producto->tipoCajaProducto_id     = 3;
                        }else{
                            $producto->tipoCajaProducto_id     = 1;
                        }

                        if($producto->update()) {
                            $productoId = $producto->id;

                            $sucursales = sucursales::all();

                            foreach($sucursales as $sucursal){

                                $almacen = almacenes::where('sucursal_id', $sucursal->id)
                                                    ->where('producto_id', $productoId)
                                                    ->first();

                                if($almacen){
                                    if($sucursal->nombre == $sucursalExcel){
                                        $almacen->stock = $stock;
                                        $almacen->total = $stock;
                                        $almacen->update();
                                    }
                                    
                                }else{
                                    $almacenNuevo = new almacenes;
                                    $almacenNuevo->sucursal_id   = $sucursal->id;
                                    $almacenNuevo->producto_id   = $productoId;
                                    $almacenNuevo->vendido       = 0;
                                    if($sucursal->nombre == $sucursalExcel){
                                        $almacenNuevo->stock         = $stock;
                                        $almacenNuevo->total         = $stock;
                                    }else{
                                        $almacenNuevo->stock         = 0;
                                        $almacenNuevo->total         = 0;
                                    }
                                    $almacenNuevo->save();
                                }
                            }
                            
                        }else{
                            $respuesta = false;
                            $logs['PRODUCTO_NO_ACTUALIZADO'][] = $codigoProducto." EN LINEA: ".$i;
                        }

                    }else{
                        $productoNuevo = new productos;
                        $productoNuevo->codigo      = $codigoProducto;
                        $productoNuevo->marca_id    = $idMarca;
                        $productoNuevo->tipo_id     = $idTipo;
                        $productoNuevo->nombre      = $descripcionProducto;
                        $productoNuevo->cantidad    = $stock;
                        $productoNuevo->total       = $stock;
                        $productoNuevo->vendido     = 0;
                        $productoNuevo->precio      = $precioVenta;
                        if($caja == "CAJA"){
                            $productoNuevo->tipoCajaProducto_id     = 3;
                        }else{
                            $productoNuevo->tipoCajaProducto_id     = 1;
                        }

                        if($productoNuevo->save()) {

                            $productoId = $productoNuevo->id;

                            $sucursales = sucursales::all();

                            foreach($sucursales as $sucursal){
                                $almacenNuevo = new almacenes;
                                $almacenNuevo->sucursal_id   = $sucursal->id;
                                $almacenNuevo->producto_id   = $productoId;
                                $almacenNuevo->vendido       = 0;
                                if($sucursal->nombre == $sucursalExcel){
                                    $almacenNuevo->stock         = $stock;
                                    $almacenNuevo->total         = $stock;
                                }else{
                                    $almacenNuevo->stock         = 0;
                                    $almacenNuevo->total         = 0;
                                }
                                $almacenNuevo->save();
                            }

                        }else{
                            $respuesta = false;
                            $logs['PRODUCTO_NO_CREADO'][] = $codigoProducto." EN LINEA: ".$i;
                        }
                    }
                }

            }else{
                $respuesta = false;
                $logs['ARCHIVO_NO_CARGADO'] = "EL ARCHIVO NO SE PUDO CARGAR AL SISTEMA";
            }

        } catch (Exception $e) {
            $mensajedev = $e->getMessage();
        }

        $requestsalida = response()->json([
            "respuesta"      => $respuesta,
            "mensaje"        => $mensaje,
            "datos"          => $datos,
            "mensajeDetalle" => $mensajeDetalle,
            "mensajedev" => $mensajedev,
            "logs" => $logs,
        ]);


        return $requestsalida;

    }
}
