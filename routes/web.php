<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/inicio', function () {
    return view('web.home.index');
});

Route::get('/contactanos', function () {
    return view('web.contactanos.index');
});


Route::get('/', 'HomeController@index')->name('home');
// Route::get('/', function () {
//     return view('web.home.index');
//     // return view('auth.login');
// });

Route::get('/queso', function () {
    return view('react.app');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/perfil', 'perfilUsuario@index')->name('perfil');
Route::post('/consult/ruc/{ruc}', 'consultaIdentidad@rucConsult')->name('consult.ruc');
Route::post('/consult/dni/{dni}', 'consultaIdentidad@dniConsult')->name('consult.dni');
Route::post('/producto/buscar', 'ProductosController@buscarProducto');
Route::post('/producto/buscar/caja', 'ProductosController@buscarCajaProducto');
Route::post('/almacen/buscar/abrirCaja', 'panel\cajasProductosController@consultaCaja');

// ALMACEN
// ALMACEN VISTAS
    Route::get('/almacen', 'panel\almacen@index')->name('almacen.index');
    Route::get('/almacen/tb_almacen', 'panel\almacen@tb_almacen')->name('almacen.tabla');

    Route::get('/almacen/entrada', 'panel\entrada@index')->name('almacen.entrada.index');
    Route::get('/almacen/entrada/tb_entradas', 'panel\entrada@tb_entradas')->name('almacen.entrada.tabla');

    Route::get('/almacen/proveedor', function () {
        return view('react.app');
    });
    Route::get('/almacen/proveedores', 'panel\proveedor@index')->name('almacen.proveedor.index');
    Route::get('/almacen/proveedores/tb_proveedores', 'panel\proveedor@tb_proveedores')->name('almacen.proveedor.tabla');

    Route::get('/almacen/tiposproductos/datos', 'tiposController@datos')->name('almacen.tiposproductos.datos');
    Route::get('/almacen/tiposproductos', 'tiposController@index')->name('almacen.tiposproductos.index');
    Route::get('/almacen/tiposproductos/tb_tiposProductos', 'tiposController@tb_tiposProductos')->name('almacen.tiposproductos.tabla');

    Route::get('/almacen/marcas/datos', 'MarcasController@datos')->name('almacen.marcas.datos');
    Route::get('/almacen/marcas', 'MarcasController@show')->name('almacen.marcas.index');
    Route::get('/almacen/marcas/tb_marcas', 'MarcasController@tb_marcas')->name('almacen.marcas.tabla');

// ALMACEN ENVIO
    Route::post('/almacen/proveedor/crear', 'panel\almacen@proveedorCreear')->name('almacen.proveedor.crear');
    Route::post('/almacen/marca/crear', 'panel\almacen@marcaCrear')->name('almacen.marca.crear');
    Route::post('/almacen/tipo/crear', 'panel\almacen@tipoCrear')->name('almacen.tipo.crear');
    Route::post('/almacen/producto/crear', 'panel\almacen@productoCrear')->name('almacen.producto.crear');
    Route::post('/almacen/entrada/crear', 'panel\almacen@entradaCrear')->name('almacen.entrada.crear');
    Route::post('/almacen/entrada/editar', 'panel\entrada@edit')->name('almacen.entrada.editar');
    Route::post('/almacen/entrada/eliminar', 'panel\entrada@destroy')->name('almacen.entrada.eliminar');
    Route::post('/almacen/producto/editar', 'panel\almacen@productoEditar')->name('almacen.producto.editar');
    Route::post('/almacen/caja/crear', 'panel\almacen@cajaProductoCrear')->name('almacen.caja.crear');
    Route::post('/almacen/caja/consulta', 'panel\cajasProductosController@consultaCaja')->name('almacen.caja.consulta');
    Route::post('/almacen/caja/abrir', 'panel\cajasProductosController@abrirCaja')->name('almacen.caja.abrir');
    Route::post('/almacen/caja/cerrar', 'panel\cajasProductosController@cerrarCaja')->name('almacen.caja.cerrar');

    Route::post('/almacen/proveedores/editar', 'panel\proveedor@proveedorEditar')->name('almacen.proveedor.editar');
    Route::post('/almacen/tiposproductos/editar', 'tiposController@tiposProductosEditar')->name('almacen.tiposproductos.editar');
    Route::post('/almacen/tiposproductos/eliminar', 'tiposController@tiposProductosEliminar')->name('almacen.tiposproductos.eliminar');

    Route::post('/almacen/marcas/editar', 'MarcasController@marcasEditar')->name('almacen.marcas.editar');
    Route::post('/almacen/marcas/eliminar', 'MarcasController@marcasEliminar')->name('almacen.marcas.eliminar');

// ALMACEN LOADS
    Route::get('/almacen/load/tiposProductos', function () {
        return view('almacen.loads.tiposProductos');
    });

    Route::get('/almacen/load/productos', function () {
        return view('almacen.loads.productos');
    });

    Route::get('/almacen/load/marcas', function () {
        return view('almacen.loads.marcas');
    });

    Route::get('/almacen/entrada/pdf/{idEntrada}', 'panel\entrada@verDetalleEntrada')
            ->name('entrada.pdf');

// TRANSFERENCIAS
Route::get('/almacen/transferencias', 'panel\transferencia\transferenciaController@index')->name('transferencia.index');
Route::get('/almacen/transferencias/tb_transferencias', 'panel\transferencia\transferenciaController@tb_transferencias')->name('transferencia.tabla');
Route::post('/almacen/transferencias/crear', 'panel\transferencia\transferenciaController@crear')->name('transferencia.crear');
Route::post('/almacen/transferencias/eliminar', 'panel\transferencia\transferenciaController@eliminar')->name('transferencia.eliminar');


// VENTAS
    Route::get('/ventas', 'panel\ventasController@index')->name('ventas.index');
    Route::get('/ventas/tb_ventas', 'panel\ventasController@tb_ventas')->name('ventas.tb_ventas');
    Route::get('/ventas/tb_ventas_detalladas', 'panel\ventasController@tb_ventas_detalladas')->name('ventas.tb_ventas_detalladas');

    Route::get('/ventas/tb_buscarProducto', 'panel\ventasController@tb_buscarProducto')->name('ventas.tb_buscarProducto');
    Route::get('/ventas/factura/serie', 'tiposcomprobanteController@facturaReact');
    Route::get('/ventas/boleta/serie', 'tiposcomprobanteController@boletaReact');
    Route::get('/ventas/venta/serie', 'tiposcomprobanteController@ventaReact');

    Route::post('/ventas/cancelar/venta/interna', 'panel\ventasController@cancelarVentaInterna')
                ->name('ventas.cancelar.venta.interna');




    // VENTAS LOADS
        Route::get('/ventas/loads/frmfactura', function () {
            return view('ventas.loads.frm_emitirFactura');
        });
        Route::get('/ventas/loads/frmboleta', function () {
            return view('ventas.loads.frm_emitirBoleta');
        });

        Route::get('/ventas/pdf/{idVenta}', 'panel\ventasController@verDetalleVenta')
                ->name('ventas.pdf');

        Route::get('/ventas/xml/{idVenta}', 'panel\ventasController@descargarXml')
                ->name('ventas.xml');

        Route::post('/ventas/notaCredito', 'panel\ventasController@notaCredito')
                ->name('ventas.notaCredito');

        Route::post('/ventas/imprimir/venta', 'panel\ventasController@imprimirVenta')
                ->name('ventas.imprimir.venta');

    // VENTAS ENVIOS
        // EMITIR FACTURA ELECTRONICA
        Route::post('/venta/emitirfactura', 'panel\ventasController@emitirFactura')->name('venta.emitir');
        Route::post('/venta/guardarEmitirfactura', 'panel\ventasController@guardarEmitirFactura')->name('venta.guardarEmitir');

        // EMITIR BOLETA ELECTRONICA
        Route::post('/venta/emitirBoleta', 'panel\ventasController@emitirBoleta')->name('venta.boleta.emitir');
        Route::post('/venta/guardaremitirBoleta', 'panel\ventasController@guardarEmitirBoleta')->name('venta.boleta.guardarEmitir');

        // EMITIR VENTA INTERNA
        Route::post('/venta/guardarVentaInterna', 'panel\ventasController@guardarVentaInterna')->name('venta.interna.emitir');

        // ENVIAR COMPROBANTE A LA SUNAT
        Route::post('/ventas/comprobante/emitir', 'panel\ventasController@comprobanteEmitir')->name('venta.comprobante.emitir');

        // CREAR PRODUCTO TEMPORAL
        Route::post('/ventas/crear/productoTemporal', 'panel\ventasController@productoTemporalCrear')->name('venta.crear.productoTemporal');


//REPORTE DE MOVIMIENTOS
    Route::post('/movimientos/reporte', 'panel\movimientos\movimientosController@reporte')->name('movimientos.reporte');


// CONFIGURACION
// DESCUENTOS
    // Route::get('/configuracion/descuentos', 'panel\Configuraciones\descuentosController@index')
    //         ->name('configuraciones.descuentos.index');
    Route::get('/configuracion/descuentos', function () {
        return view('react.app');
    });

    Route::get('/configuracion/ofertas', function () {
        return view('react.app');
    });

    Route::get('/configuracion/carga-archivos', function () {
        return view('react.app');
    });

    Route::get('/configuracion/descuentos/tb_descuentos', 'panel\Configuraciones\descuentosController@tb_descuentos')
            ->name('configuraciones.descuentos.tabla');

    Route::post('/configuraciones/descuentos/crear', 'panel\Configuraciones\descuentosController@descuentoCrear')
            ->name('configuraciones.descuentos.crear');

    Route::post('/configuraciones/descuentos/editar', 'panel\Configuraciones\descuentosController@descuentoEditar')
            ->name('configuraciones.descuentos.editar');

    Route::post('/configuraciones/descuentos/eliminar', 'panel\Configuraciones\descuentosController@descuentoEliminar')
            ->name('configuraciones.descuentos.eliminar');
            
// USUARIOS
    Route::get('/configuracion/usuarios', 'panel\Configuraciones\usuariosController@index')->name('configuraciones.usuarios.index');
    Route::get('/configuracion/usuarios/tb_usuarios', 'panel\Configuraciones\usuariosController@tb_usuarios')->name('configuraciones.usuarios.tabla');
    Route::get('/configuracion/usuarios/tb_sucursales', 'panel\Configuraciones\usuariosController@tb_sucursales')->name('configuraciones.usuarios.tb_sucursales');
    Route::post('/configuraciones/usuarios/editar', 'panel\Configuraciones\usuariosController@usuarioEditar')->name('configuraciones.usuarios.editar');
    Route::post('/configuraciones/usuarios/crear', 'panel\Configuraciones\usuariosController@usuarioCrear')->name('configuraciones.usuarios.crear');

Route::post('/producto/eliminar', 'ProductosController@eliminarProducto')->name('producto.eliminar');
Route::get('/sunats', 'sunat@sunat')->name('sunat');
Route::get('/villca', 'sunat@villca');
// Route::get('/codigo', 'ProductosController@codigo');


// consultarComprobante
Route::get('/consultar', 'consultar\consultarComprobanteController@index')
        ->name('consultar.index');;
Route::post('/consultar/captcha', 'consultar\consultarComprobanteController@consultaCaptcha')
        ->name('consultar.captcha');
Route::get('/consultar/pdf/{idVenta}', 'consultar\consultarComprobanteController@consultarPdfVenta')
        ->name('consultar.pdf');
Route::get('/consultar/xml/{idVenta}', 'consultar\consultarComprobanteController@consultarXml')
        ->name('consultar.xml');

// CONSULTAR TODAS LAS SUCURSALES
Route::get('/consultar/sucursales', 'consultar\consultarSucursales@consultarSucursales')
        ->name('consultar.sucursales');

// CONSULTAR SUCURSALES DE UN USUARIO
Route::get('/consultar/sucursales/usuario', 'consultar\consultarSucursales@consultarSucursalesUsuario')
        ->name('consultar.sucursales.usuario');


Route::get('/prueba/{id}', 'prueba\pruebaController@notaCredito');

Route::get('qr', function () {
    \QrCode::size(500)
              ->format('png')
              ->generate('ItSolutionStuff.com', public_path('qrcode.png'));
    return view('qrCode');
  });
  //Route::get('/pepe', 'panel\movimientos\movimientosController@reporte');
  Route::get('/prod', 'prueba\pruebaController@productosalmacen');




  // Caja de venta
Route::post('/caja/venta/estado', 'top\cajaVentaController@cajaVentaActual');
Route::post('/caja/venta/estado/totalcierre', 'top\cajaVentaController@totalCierreActual');
Route::post('/caja/venta/aperturar', 'top\cajaVentaController@aperturarCaja');
Route::post('/caja/venta/cierre', 'top\cajaVentaController@cierreCajaVenta');

// Gasto
Route::post('/gasto/registrar', 'top\gastosController@registrarGasto');

// Ingreso
Route::post('/ingreso/registrar', 'top\ingresosCajaVentaController@registarIngresoCajaVenta');

// CIERRE DE CAJAS MENU
Route::get('/cierrescajas', 'panel\cajaVenta\cajaVentaController@index');
Route::get('/cierrescajas/tb_cierreCaja', 'panel\cajaVenta\cajaVentaController@tb_cajaVenta');
Route::get('/cierrescajas/tb_cajaVenta_detallada', 'panel\cajaVenta\cajaVentaController@tb_cajaVenta_detallada');
//
Route::get('/mostrar/{id}', 'panel\cajaVenta\cajaVentaController@tb_cajaVenta');

// MODULO CAJA

    //GESTIONAR GASTOS
    Route::get('/caja/gastos', 'panel\cajaVenta\gastosController@index');
    Route::get('/caja/gastos/tb_gastos', 'panel\cajaVenta\gastosController@tb_gastos');

    //GESTIONAR INGRESOS
    Route::get('/caja/ingresos', 'panel\cajaVenta\ingresosController@index');
    Route::get('/caja/ingresos/tb_ingresos', 'panel\cajaVenta\ingresosController@tb_ingresos');
    













// VERSION 2

Route::post('/versiondos/login', 'Versiondos\Login\LoginControllerLoginController@login');