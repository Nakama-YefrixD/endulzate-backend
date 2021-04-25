<?php 
  use\App\Http\Controllers\ProductosController; 
  use\App\Http\Controllers\TiposMonedaController;
  use\App\Http\Controllers\tiposcomprobanteController;
  use\App\Http\Controllers\descuentosProductoController;
  $productos = ProductosController::buscadorProductos();
  $tiposMoneda = TiposMonedaController::index();
  $tiposcomprobante = tiposcomprobanteController::boleta();
  $descuentosProducto = descuentosProductoController::index();
  $fechaActual = date('Y-m-d');
//   $otros = ProductosController::buscadorProductos();
  
  
?>

<h6 class="card-title">BOLETA ELECTRÓNICA:</h6>
<form method="post" role="form" data-toggle="validator" id="frm_emitirBoleta">
    @csrf
    <div class="form-group">
        <div class="row">
            <div class="col-3">
                <label><i class="mdi mdi-barcode"></i>   Serie:</label>
                <input type="hidden" name="tipoComprobante" id="tipoComprobante" value="{{ $tiposcomprobante->id }}" class="form-control" readonly="readonly" >
                <input type="text" name="serieVenta" id="serieVenta" value="{{ $tiposcomprobante->serie }}" class="form-control" readonly="readonly">
            </div>
            <div class="col-3">
                <label> <i class="mdi mdi-file-document-box"></i>    Nº Boleta:</label>
                <input type="number" class="form-control" name="facturaVenta" id="facturaVenta" value="{{ $tiposcomprobante->correlativo }}">
            </div>
            <div class="col-3">
                <label> <i class="mdi mdi-calendar-text"></i>    Fecha de emisión:</label>
                <input type="text" class="form-control" value ="{{ $fechaActual }}" name="dateFactura" id="dateFactura">
            </div>
            <div class="col-3">
                <label> <i class="mdi mdi-cash-multiple"></i>  Moneda:</label>
                <div class="input-group">
                    <select class="form-control" name="tipoMoneda" id="tipoMoneda" style="width: 100%;">
                        @foreach($tiposMoneda as $tipoMoneda)
                            <option value="{{ $tipoMoneda->id }}" > {{ $tipoMoneda->nombre }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <h6 class="card-title">CLIENTE:</h6>
    <div class="form-group" >
        <div class="row">
            <div class="col-3">
                <label>  <i class="mdi mdi-account-card-details"></i>   Tipo de Documento: *</label>
                <div class="input-group">
                    <select class="form-control" name="tipoDocumento" id="tipoDocumento" style="width: 100%;">
                        <option value="1" > DNI </option>
                        <option value="4" > OTROS </option>
                    </select>
                </div>
            </div>
            <div class="col-3" id="contenedorNumeroDocumento">
                <label>N° de Documento: *</label>
                <input type="number" class="form-control" name="numeroDocumento" id="numeroDocumento">
            </div>
            <div class="col-6" id="contenedorNombreCliente">
                <label>Nombre del cliente: *</label>
                <input type="text" class="form-control" name="nombreCliente" id="nombreCliente">
            </div>
            <input type="hidden" class="form-control" name="direccion" id="direccion" value="-">
        </div>
    </div>
    <h6 class="card-title">DETALLE DOCUMENTO:</h6>
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered" id="tb_products">
                <thead>
                    <tr>
                        <!-- <th width="5%;" >Codigo</th> -->
                        <th width="25%;">Producto</th>
                        <th>Cantidad</th>
                        <th >Disponible</th>
                        <th width="15%;">Precio</th>
                        <th>Descuento</th>
                        <th>SubTotal</th>
                        <th>Total</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- <tr> -->
                        <!-- <td class="codigoProducto">
                            <select class="form-control productos " name="codigoProducto[]" id="codigoProducto" style="width: 100%;">
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}" 
                                        precio="{{ $producto->precio }}" disponible="{{ $producto->cantidad }}" > 
                                        {{ $producto->id }}</option>
                                @endforeach
                            </select>
                        </td> -->
                        <!-- <td>
                            <select class="form-control productos " name="nombreProducto[]" id="nombreProducto" style="width: 100%;">
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}" 
                                    precio="{{ $producto->precio }}"  disponible="{{ $producto->cantidad }}"> {{ $producto->nombre }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="cantidad">
                            <input type="text" class="form-control c_quantity" name="cantidad[]"  value="0">
                        </td>
                        <td class="disponible">
                            <span>0</span>
                            <input type="hidden" class="form-control disponible" name="disponible[]" value="0" readonly="">
                        </td>
                        <td class="precio">
                            <span>0</span>
                            <input type="hidden" class="form-control precio" name="precio[]" value="0" readonly="" style="width: 100%;">
                        </td>
                        <td>
                            <span>0</span>
                            <input type="hidden" class="form-control descuento" name="descuento[]" value="0" readonly="">
                        </td>
                        <td class="subtotal">
                            <span>0</span>
                            <input type="hidden" class="form-control subtotal" name="subtotal[]" value="0" readonly="" style="width: 100px;">
                        </td>
                        <td class="total">
                            <span>0</span>
                            <input type="hidden" class="form-control total" name="total[]" value="0" readonly="" style="width: 100px;">
                        </td>
                        <td>
                            
                        </td> -->
                    <!-- </tr> -->
                </tbody>
            </table>
        </div>
        
        <div class="col-12">
            <br>
            
            <button type="button" class="btn btn-warning" id="btnAddProduct">
                <i class="mdi mdi-plus-circle"></i>   AGREGAR PRODUCTO AL DETALLE
            </button>
            <br>
        </div>
    </div><br>
    <div class="col-md-12" style="margin-top: 15px;">
        <div class="row">
            <div class="col-sm-7">
                <div class="content-group">
                    <h6>Observación:</h6>
                    <textarea class="form-control" name="observacionVenta" id="exampleTextarea1" rows="8" placeholder="Escribe aquí una observación">SN</textarea>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="content-group">
                    <h6>Resumen:</h6>
                    <div class="table-responsive no-border">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Descuento:</th>
                                    <td class="text-right">
                                        S/. <span id="descuentoVentaTexto">0</span>
                                        <input type="hidden" name="descuentoVenta" id="descuentoVenta" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Subtotal:</th>
                                    <td class="text-right">
                                        S/. <span id="subTotalVentaTexto">0</span>
                                        <input type="hidden" name="subTotalVenta" id="subTotalVenta" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <th>IGV: <span class="text-regular">(18%)</span></th>
                                    <td class="text-right">
                                        S/. <span id="igvVentaTexto">0</span>
                                        <input type="hidden" name="igvVenta" id="igvVenta" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total:</th>
                                    <td class="text-right text-primary"><h5 class="text-semibold">
                                        S/. <span id="totalVentaTexto">0</span></h5>
                                        <input type="hidden" name="totalVenta" id="totalVenta" value="2">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="form-group boton">
        <div class="row">
            <div class="col-6">
                <button type="button" class=" addexis form-control btn btn-block btn-primary btn-lg" id="emitirBoleta">
                    EMITIR BOLETA ELECTRÓNICA</button>
            </div>
            <div class="col-6">
                <button type="button" class=" addexis form-control btn btn-block btn-success btn-lg" id="guardarBoleta">
                    GUARDAR BOLETA ELECTRÓNICA</button>
            </div>
        </div>
        
    </div>
</form>

<div id="agregarProductoDetalleModal" class="modal fade bd-agregarProductoDetalleModal-lg" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> Buscar producto especifico </h4>
                </div>
                <div class="modal-body">
                    
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Buscar</h4>
                                <div class="row">
                                    <div class="col-3">
                                        <label>Buscar Codigo</label>
                                        <input type="text" class="form-control form-control-lg" name="buscar_tb_codigo" id="buscar_tb_codigo">
                                    </div>
                                    <div class="col-2">
                                        <label>Buscar Marca</label>
                                        <input type="text" class="form-control form-control-lg" name="buscar_tb_marca" id="buscar_tb_marca">
                                    </div>
                                    <div class="col-2">
                                        <label>Buscar Tipo</label>
                                        <input type="text" class="form-control form-control-lg" name="buscar_tb_tipo" id="buscar_tb_tipo">
                                    </div>
                                    <div class="col-3">
                                        <label>Buscar Nombre</label>
                                        <input type="text" class="form-control form-control-lg" name="buscar_tb_nombre" id="buscar_tb_nombre">
                                    </div>
                                    <div class="col-2">
                                        <label>Buscar Precio</label>
                                        <input type="text" class="form-control form-control-lg" name="buscar_tb_nombre" id="buscar_tb_precio">
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <button type="button" class="btn btn-warning" id="btnAddProductoTemporal">
                            <i class="mdi mdi-plus-circle"></i>   AGREGAR PRODUCTO TEMPORAL
                        </button>

                    <div class="card-body" id="agregarProductoDetalleModalBody">
                        <table class="table table-bordered dataTables_length" id="tb_buscarProducto" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Marca</th>
                                    <th>Tipo</th>
                                    <th>Disponibles</th>
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>Seleccionar</th>
                                </tr>
                            </thead>
                        </table>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="productoTemporalModal" class="modal fade bd-productoTemporalModal" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> Agregar Producto </h4>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_producto">
                            @csrf
                            <div class="form-group" >
                                <div class="row">
                                    <div class="col-12">
                                        <label>Codigo (OPCIONAL)</label>
                                        <input type="text" class="form-control" name="codigoProductoNuevo" id="codigoProductoNuevo">
                                    </div>
                                    <div class="col-12" id="alertaCodigo">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12">
                                        <label>Nombre del producto</label>
                                        <input type="text" class="form-control" name="nombreProductoNuevo" id="nombreProductoNuevo">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12">
                                        <label>Precio con IGV(18%)</label>
                                        <input type="text" name="precioVentaProducto" id="precioVentaProducto" class="form-control"
                                            pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" placeholder="S/1,000,000.00" >
                                        <!-- <input type="text" class="form-control" name="producto" id="producto" > -->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group boton">
                                <button type="button" class="addexis form-control btn btn-block btn-success btn-lg" id="crearProducto">
                                    Agregar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


        
<script type="text/javascript" src="{{ asset('js/ventas/comprobantes/emitirFactura/buscarProductoAgregarDetalle.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ventas/comprobantes/emitirFactura/seleccionarProductoDetalle.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ventas/comprobantes/emitirFactura/calcularCambioProducto.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ventas/comprobantes/emitirFactura/calcularCantidadProducto.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ventas/comprobantes/emitirBoleta/emitirBoleta.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ventas/comprobantes/emitirBoleta/guardarBoleta.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ventas/comprobantes/emitirFactura/calcularDescuentoProducto.js') }}"></script>

<script type="text/javascript" src="{{ asset('js/ventas/comprobantes/emitirBoleta/agregarProductoTemporal.js') }}"></script>

<script type="text/javascript">
    $('body').on('change','#tipoDocumento', function() {
        let tipoDocumento = $('#tipoDocumento').val();
        console.log('xd')
        if(tipoDocumento == 4){
            // $('#numeroDocumento').val("123")
            $("#numeroDocumento").val("00000000");
            $("#nombreCliente").val("CLIENTE 0001");
            $('#contenedorNombreCliente').hide();
            $('#contenedorNumeroDocumento').hide();
        }else{
            $("#numeroDocumento").val("");
            $("#nombreCliente").val("");
            $('#contenedorNombreCliente').show();
            $('#contenedorNumeroDocumento').show();
        }
        

    });

    $('#btnAddProduct').on('click', function() {
        $('#agregarProductoDetalleModal').modal('show');
    });

    $('body').on('click', '.remove', function() {
        $(this).parent().parent().remove();
        calcularTotalVenta();
    });
    
    $('#numeroDocumento').on('keyup', function() {
        let url = '';
        if($(this).val().length == 8) {
            url = '/consult/dni/' + $(this).val();
            datosCliente(url);
        }
        
    });

    function datosCliente(url)
    {
        $.ajax({
            url: url,
            type: 'post',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            dataType: 'json',
            success: function(response) {
                    // $('#telefonoProveedor').val(response['telefonos']);
                    $('#nombreCliente').val(response['nombres']+" "+response['apellidoPaterno']+" "+response['apellidoMaterno']);
                    // $('#tipoProveedor').val(response['tipo']);
            },
            error: function(response) {
                toastr.info('El ruc no existe.');
            }
        });
    }

    function calcularTotalVenta()
    {
        // VARIABLES DE RESULTADO DE LA VENTA
        let cantidad  = 0;
        let descuento  = 0;
        let descuentoVenta  = 0;
        let subTotalVenta   = 0;
        let igvVenta        = 0;
        let totalVenta      = 0;

        $('#tb_products tbody tr').each(function(index, tr) {
            cantidad =$(tr).find('.cantidad input').val();
            descuento = $(tr).find('.descuento input[name="descuento[]"]').val();
            descuentoVenta = (descuentoVenta * 1) + (descuento* cantidad);
            subTotalVenta = (subTotalVenta * 1) + ($(tr).find('.subtotal input').val() * 1);
            totalVenta = (totalVenta * 1) + ($(tr).find('.total input').val() * 1);
        });
        //miomio
        $('#descuentoVentaTexto').html(descuentoVenta.toFixed(2));
        $('#subTotalVentaTexto').html(subTotalVenta.toFixed(2));
        $('#igvVentaTexto').html((totalVenta - subTotalVenta).toFixed(2));
        $('#totalVentaTexto').html( totalVenta.toFixed(2));

        $('#descuentoVenta').val(descuentoVenta.toFixed(2));
        $('#igvVenta').val((totalVenta - subTotalVenta).toFixed(2));
        $('#subTotalVenta').val(subTotalVenta.toFixed(2));
        $('#totalVenta').val(totalVenta.toFixed(2));
    }

    $(document).ready(function() {
        $('#dateFactura').datepicker({
            'format': 'yyyy-mm-dd',
            'autoclose': true
        });
    })
</script>