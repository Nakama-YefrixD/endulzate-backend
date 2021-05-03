<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/codigo/buscar', 'api\agregarProductoController@buscarCodigo');
Route::post('/codigo/qr/buscar', 'api\agregarProductoController@buscarCodigoQr');
Route::post('/nombre/buscar', 'api\agregarProductoController@buscarNombre');
Route::post('/marca/buscar', 'api\agregarProductoController@buscarMarca');
Route::post('/tipo/buscar', 'api\agregarProductoController@buscarTipos');

Route::post('/agregar/productoExistente', 'api\agregarProductoController@agregarProductoExistente');
Route::post('/agregar/nuevoProducto','api\agregarProductoController@nuevoProducto');


// IMPRESIONES

Route::get('/imprimir/venta/{idVenta}','impresiones\imprimirVentaController@imprimirVenta');
Route::get('/imprimir/aperturarCaja/{idCajaVenta}','impresiones\imprimirAperturaCajaController@aperturarCaja');
Route::get('/imprimir/cierreCaja/{idCajaVenta}','impresiones\imprimirCierreCajaController@cierreCaja');




// VERSION 2


Route::post('/versiondos/login', 'Versiondos\Login\LoginController@login');

Route::post('/versiondos/cargar-data/almacen', 'Versiondos\Login\LoginController@login');