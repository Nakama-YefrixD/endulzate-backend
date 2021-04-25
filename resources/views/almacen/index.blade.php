@extends('layouts.blank')
@section('title')
    Almacen
@endsection
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Modulos de agregar</h4>
            <button type="button" data-toggle="modal" data-target="#entradaModal" class="btn btn-gradient-primary btn-rounded btn-fw">Agregar Entrada</button>
            <button type="button" data-toggle="modal" data-target="#productoModal" class="btn btn-gradient-success btn-rounded btn-fw">Agregar Producto</button>
            <button type="button" data-toggle="modal" data-target="#marcaModal" class="btn btn-gradient-info btn-rounded btn-fw">Agregar Marca</button>
            <button type="button" data-toggle="modal" data-target="#proveedorModal" class="btn btn-gradient-warning btn-rounded btn-fw">Agregar Proveedor</button>
        </div>
    </div> 
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Buscar</h4>
            <div class="row">
                <div class="col-3">
                    <label>Buscar Codigo</label>
                    <input type="text" class="form-control form-control-lg" name="buscar_tb_codigo" id="buscar_tb_codigo">
                </div>
                <div class="col-3">
                    <label>Buscar Marca</label>
                    <input type="text" class="form-control form-control-lg" name="buscar_tb_marca" id="buscar_tb_marca">
                </div>
                <div class="col-3">
                    <label>Buscar Tipo</label>
                    <input type="text" class="form-control form-control-lg" name="buscar_tb_tipo" id="buscar_tb_tipo">
                </div>
                <div class="col-3">
                    <label>Buscar Nombre</label>
                    <input type="text" class="form-control form-control-lg" name="buscar_tb_nombre" id="buscar_tb_nombre">
                </div>
                    
            </div>
        </div>
    </div> 
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Almacen de productos</h4>
            <table id="tb_almacen" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Marca</th>
                        <th>Tipo</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
            </table>  
        </div>
    </div>
</div>



<div id="entradaModal" class="modal fade bd-entradaModal-modal-lg" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> Agregar Entrada </h4>
                    <div class="form-group row">
                          <div class="col-sm-3">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="membershipRadios" id="cerrar" value="1" checked="">
                                Cerrar automaticamente
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="membershipRadios" id="abrir" value="0">
                                Mantenerla abierta
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                        </div>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_entrada">
                        <!-- <form role="form" method="post" accept-charset="utf-8" id="frm_entrada" 
                                enctype="multipart/form-data"> -->
                            @csrf
                            <input type='hidden' value='1' name= "cantidadProductos" id="cantidadProductos">
                            <input type='hidden' value='0' name= "agregandoProveedor" id="agregandoProveedor">
                            <input type='hidden' value='0' name= "agregandoProducto" id="agregandoProducto">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label>Selecciona al proveedor</label>
                                        <div class="input-group">
                                            <select class="form-control" name="proveedor" id="proveedores" style="width: 90%;">
                                                @foreach($proveedores as $proveedor)
                                                    <option value="{{ $proveedor->id }}" > {{ $proveedor->nombre }} </option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-sm btn-gradient-primary" id="agregarNuevoProveedor" type="button"><i class="mdi mdi-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <label>Numero de factura</label>
                                        <input type="text" class="form-control" name="factura" id="factura" >
                                    </div>
                                    <div class="col-3">
                                        <label>Fecha</label>
                                        <input type="text" class="form-control" id="date" name="fecha" placeholder="YYYY-MM-DD" autocomplete="off" >
                                    </div>
                                        
                                </div>
                            </div>
                            <div class="form-group" id="listProductos">
                                <div class="row">
                                    <div class="col-4">
                                        <label>Producto de entrada</label><br>
                                        <select class="form-control listProductos" id="productos" name="producto[]" style="width: 100%;" >
                                            @foreach($productos as $producto)
                                                <option value="{{ $producto->id }}" >{{ $producto->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2 precioCompraContainer">
                                        <label>Precio de compra</label>
                                        <input type="text" name="precio[]" id="precio" class="form-control precioCompra"
                                            pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" placeholder="S/1,000,000.00">
                                    </div>
                                    <div class="col-2 cantidadCompraContainer">
                                        <label>Cantidad</label>
                                        <input type="number" class="form-control cantidadProductoEntrada" name="cantidad[]" id="cantidad" >
                                    </div>
                                    <div class="col-2 importeCompraContainer">
                                        <label>Importe</label>
                                        <input type="text" name="importe[]" id="importe" class="form-control " pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" placeholder="S/1,000,000.00">
                                    </div>
                                    <div class="col-1">
                                        <br>
                                        <button type="button" class="btn btn-gradient-primary btn-rounded btn-icon " id="agregarNuevoProducto">
                                            <i class="mdi mdi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="button" id="agregarProducto" class="btn btn-gradient-warning btn-rounded btn-fw">Agregar producto</button>
                            </div>
                            
                            <div class="form-group boton">
                                <button type="button" class="addexis form-control btn btn-block btn-success btn-lg" id="crearEntrada">
                                    Agregar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="productoModal" class="modal fade bd-productoModal" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> Agregar Producto </h4>
                    <div class="form-group row">
                          <div class="col-sm-6">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="productoEstado" id="cerrarProducto" value="1" checked="">
                                Cerrar automaticamente
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="productoEstado" id="abrirProducto" value="0">
                                Mantenerla abierta
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                        </div>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_producto">
                        <!-- <form role="form" method="post" accept-charset="utf-8" id="frm_producto" 
                                enctype="multipart/form-data"> -->
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12">
                                        <label>Nuevo tipo de producto</label>
                                        <div class="input-group">
                                            <input type="text"  placeholder="Si no existe el tipo de producto agregalo" class="form-control" name="nuevoTipoProducto" id="nuevoTipoProducto" >
                                            <div class="input-group-append">
                                                <button  id="crearTipoProducto" class="btn form-control btn-sm btn-gradient-primary" type="button"><i class="mdi mdi-plus"></i></button>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" >
                                <div class="row">
                                    <div class="col-12">
                                        <label>Codigo</label>
                                        <input type="text" class="form-control" name="codigoProductoNuevo" id="codigoProductoNuevo">
                                    </div>
                                    <div class="col-12" id="alertaCodigo">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label>Marcas</label>
                                        <div class="input-group">
                                            <select class="form-control" name="marcaProducto" id="marcaProducto" style="width: 100%;">
                                                @foreach($marcas as $marca)
                                                    <option value="{{ $marca->id }}" > {{ $marca->nombre }} </option>
                                                @endforeach
                                            </select>
                                            <!-- <div class="input-group-append">
                                                <button class="btn btn-sm btn-gradient-primary" type="button"><i class="mdi mdi-plus"></i></button>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label>Tipos de prodcuto</label>
                                        <div class="input-group">
                                            <select class="form-control" name="tipoProducto" id="tipoProducto" style="width: 100%;">
                                                @foreach($tipos as $tipo)
                                                    <option value="{{ $tipo->id }}" > {{ $tipo->nombre }} </option>
                                                @endforeach
                                            </select>
                                            <!-- <div class="input-group-append">
                                                <button class="btn btn-sm btn-gradient-primary" type="button"><i class="mdi mdi-plus"></i></button>
                                            </div> -->
                                        </div>
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
                                    
                                    <div class="col-6">
                                        <label>Precio de venta</label>
                                        <input type="text" name="precioVentaProductoSinIGV" id="precioVentaProductoSinIGV" class="form-control"
                                            pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" placeholder="S/1,000,000.00">
                                        <!-- <input type="text" class="form-control" name="producto" id="producto" > -->
                                    </div>

                                    <div class="col-6">
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


<div id="marcaModal" class="modal fade bd-marcaModal" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> Agregar Marca </h4>
                    <div class="form-group row">
                          <div class="col-sm-6">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="marcaEstado" id="cerrarMarca" value="1" checked="">
                                Cerrar automaticamente
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="marcaEstado" id="abrirMarca" value="0">
                                Mantenerla abierta
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                        </div>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_marca">
                        <!-- <form role="form" method="post" accept-charset="utf-8" id="frm_marca" 
                                enctype="multipart/form-data"> -->
                            @csrf
                            
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12">
                                        <label>Nombre de la marca</label>
                                        <input type="text" class="form-control" name="nombreMarca" id="nombreMarca" >
                                    </div>
                                </div>
                            </div>

                            
                            <div class="form-group boton">
                                <button type="button" class="addexis form-control btn btn-block btn-success btn-lg" id="crearMarca">
                                    Agregar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="proveedorModal" class="modal fade bd-proveedorModal" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> Agregar proveedor </h4>
                    <div class="form-group row">
                        <div class="col-sm-6">
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="proveedorEstado" id="cerrarProveedor" value="1" checked="">
                            Cerrar automaticamente
                            <i class="input-helper"></i></label>
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="proveedorEstado" id="abrirProveedor" value="0">
                            Mantenerla abierta
                            <i class="input-helper"></i></label>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_proveedor">
                        <!-- <form role="form" method="post" accept-charset="utf-8" id="frm_proveedor" 
                                enctype="multipart/form-data"> -->
                            @csrf
                            
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label>RUC del proveedor</label>
                                        <input type="number" class="form-control" name="rucProveedor" id="rucProveedor" >
                                    </div>
                                    <div class="col-6">
                                        <label>Telefono</label>
                                        <input type="number" class="form-control" name="telefonoProveedor" id="telefonoProveedor" >
                                    </div>
                                    
                                </div><br>
                                <div class="row">
                                    <div class="col-12">
                                        <label>Nombre del proveedor</label>
                                        <input type="text" class="form-control" name="nombreProveedor" id="nombreProveedor" >
                                    </div>
                                    
                                </div><br>
                                <div class="row">
                                    <div class="col-12">
                                        <label>Dirección</label>
                                        <input type="text" class="form-control" name="direccionProveedor" id="direccionProveedor" >
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="col-12">
                                        <label>Tipo</label>
                                        <input type="text" class="form-control" name="tipoProveedor" id="tipoProveedor" >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group boton">
                                <button type="button" class="addexis form-control btn btn-block btn-success btn-lg" id="crearProveedor">
                                    Agregar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="productoEditarModal" class="modal fade bd-productoEditarModal" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> Editar Producto </h4>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_editarProducto">
                            @csrf
                            <div class="form-group" >
                                <div class="row">
                                    <div class="col-12">
                                        <label>Codigo</label>
                                        <input type="hidden" name="editarIdProducto" id="editarIdProducto">
                                        <input type="text" class="form-control" name="editarCodigoProductoNuevo" id="editarCodigoProductoNuevo">
                                    </div>
                                    <div class="col-12" id="editarAlertaCodigo">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label>Marcas</label>
                                        <div class="input-group">
                                            <select class="form-control" name="editarMarcaProducto" id="editarMarcaProducto" style="width: 100%;">
                                                @foreach($marcas as $marca)
                                                    <option value="{{ $marca->id }}" > {{ $marca->nombre }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label>Tipos de prodcuto</label>
                                        <div class="input-group">
                                            <select class="form-control" name="editarTipoProducto" id="editarTipoProducto" style="width: 100%;">
                                                @foreach($tipos as $tipo)
                                                    <option value="{{ $tipo->id }}" > {{ $tipo->nombre }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12">
                                        <label>Nombre del producto</label>
                                        <input type="text" class="form-control" name="editarNombreProductoNuevo" id="editarNombreProductoNuevo">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label>Cantidad</label>
                                        <input type="text" class="form-control" name="editarCantidadProducto" id="editarCantidadProducto">
                                    </div>
                                    <div class="col-6">
                                        <label>Precio de compra</label>
                                        <input type="text" class="form-control" name="editarPrecioCosto" id="editarPrecioCosto">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12">
                                        <label>Precio con IGV(18%)</label>
                                        <input type="text" name="editarPrecioVentaProducto" id="editarPrecioVentaProducto" class="form-control"
                                            pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" placeholder="S/1,000,000.00" >
                                        <!-- <input type="text" class="form-control" name="producto" id="producto" > -->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group boton">
                                <button type="button" class="addexis form-control btn btn-block btn-success btn-lg" id="editarProducto">
                                    Editar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@section('script')
<script type="text/javascript" src="{{ asset('js/almacen/select2.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#rucProveedor').on('keyup', function() {
                let url = '';

                if($(this).val().length == 11) {
                    url = '/consult/ruc/' + $(this).val();
                    obtenerDatosProveedor(url, $('#typedocument').val());
                }
                
            });
            
        function obtenerDatosProveedor(url, typedocument)
        {
            $.ajax({
                url: url,
                type: 'post',
                data: {
                    '_token': "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                        $('#telefonoProveedor').val(response['telefonos']);
                        $('#nombreProveedor').val(response['razonSocial']);
                        $('#direccionProveedor').val(response['direccion']);
                        $('#tipoProveedor').val(response['tipo']);
                },
                error: function(response) {
                    toastr.info('El ruc no existe.');
                }
            });
        }

        let productos = '';
        var productoIndividual = [];
        let contadorProductoIndividual = 0;
        @foreach($productos as $producto)
            productoIndividual[contadorProductoIndividual]= '{{ $producto->codigo }}'+'-'+'{{ $producto->nombre }}';
            productos += '<option value="{{ $producto->id }}" >{{ $producto->nombre }}</option>';
            contadorProductoIndividual = contadorProductoIndividual+1;
        @endforeach
        $('#agregarProducto').on('click', function() {
                var cantidadProductos = $('#cantidadProductos').val();
                var nuevaCantidadProductos = $('#cantidadProductos').val(parseInt(cantidadProductos)+1);
                var cantidadProductos = $('#cantidadProductos').val();
                
                let data = '<div class="productosAgregados">';
                data += '<br>';
                data += '<div class="row">';
                data += '<div class="col-4">';
                data += '<select class="form-control productos listProductos" name="producto[]" style="width: 100%;" > id="otroAc"';
                data += productos;   
                data += '</select>';
                data += '</div>';
                data += '<div class="col-2 precioCompraContainer">';
                data += '<input type="text" name="precio[]" id="precio" class="form-control precioCompra" pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" placeholder="S/1,000,000.00">';
                data += '</div>';
                data += '<div class="col-2 cantidadCompraContainer">';
                data += '<input type="number" class="form-control cantidadProductoEntrada" name="cantidad[]" >';
                data += '</div>';
                data += '<div class="col-2 importeCompraContainer">';
                data += '<input type="text" name="importe[]" id="importe" class="form-control " pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" placeholder="S/1,000,000.00">';
                data += '</div>';
                data += '<div class="col-1">';
                data += '<button type="button" class="btn btn-gradient-danger btn-rounded btn-icon remove">';
                data += '<i class="mdi mdi-close"></i>';
                data += '</button>';
                data += '</div>';
                data += '</div>';
                $('#listProductos').append(data);
                $('.productos').select2();
                declararFormatMoney();
                // $('.listProductos ').load('/almacen/load/productos');
                
            });

            $('body').on('click', '.remove', function() {
                $(this).parent().parent().parent().remove();
                var cantidadProductos = $('#cantidadProductos').val();
                var nuevaCantidadProductos = $('#cantidadProductos').val(parseInt(cantidadProductos)-1);
            });

            $('#crearProveedor').on('click', function(e) {
                    let data = $('#frm_proveedor').serialize();
                    console.log(data);
                    $.confirm({
                        icon: 'fa fa-question',
                        theme: 'modern',
                        animation: 'scale',
                        type: 'blue',
                        title: '¿Está seguro de crear este proveedor?',
                        content: false,
                        buttons: {
                            Confirmar: function () {
                                $.ajax({
                                    url: '/almacen/proveedor/crear',
                                    type: 'post',
                                    data: data ,
                                    dataType: 'json',
                                    success: function(response) {
                                        if(response['response'] == true) {
                                            toastr.success('Se grabó satisfactoriamente el proveedor');
                                            $("#tb_almacen").DataTable().ajax.reload();
                                            $('#rucProveedor').val('');
                                            $('#nombreProveedor').val('');
                                            $('#telefonoProveedor').val('');
                                            $('#direccionProveedor').val('');
                                            $('#tipoProveedor').val('');
                                            if($('#cerrarProveedor').val() == 1){
                                                $('#proveedorModal').modal('hide');
                                                if($('#agregandoProveedor').val() == 1){
                                                    $('#agregandoProveedor').val(0);
                                                    $('#entradaModal').modal('show');
                                                }
                                            }
                                            data = '<option value="'+response['idProveedor']+'" >'+response['nombreProveedor']+'</option>';
                                            $('#proveedores').append(data);

                                            
                                        } else {
                                            // toastr.error(response.responseText);
                                            toastr.error('Ocurrio un error al momento de crear este proveedor porfavor fijate si todos los campos estan correctos');
                                        }
                                    },
                                    error: function(response) {
                                        // toastr.error(response.responseText);
                                        toastr.error('sOcurrio un error al momento de crear este proveedor porfavor fijate si todos los campos estan correctos');
                                        
                                    }
                                });
                            },
                            Cancelar: function () {
                                
                            }
                        }
                    });
            });


            $('#precioVentaProductoSinIGV').on('keyup', function() {
                let valor = $(this).val().split('S/');
                console.log(valor[1]);
                let igv = (valor[1] *18)/100;
                console.log(igv)
                let total = parseFloat(valor[1])+parseFloat(igv);
                $('#precioVentaProducto').val(total.toFixed(2));
                
            });
            
            $('#precioVentaProducto').on('keyup', function() {
                let valor = $(this).val().split('S/');
                console.log(valor[1]);
                let igv = (valor[1] *18)/100;
                console.log(igv)
                let total = parseFloat(valor[1])-parseFloat(igv);
                $('#precioVentaProductoSinIGV').val(total.toFixed(2));
                
            });
            //miomio
            $('#codigoProductoNuevo').on('keyup', function() {
                for(let x = 0; x < productoIndividual.length; x++){
                    var valorProductoIndividual = productoIndividual[x].split('-');
                    if($(this).val() == valorProductoIndividual[0]){
                        $('#alertaCodigo').html('<label style="color:red;">EL CODIGO '+valorProductoIndividual[0]+' YA EXISTE Y LE PERTENECE A '+valorProductoIndividual[1]+'</label>')
                        
                        break;
                    }else{
                        $('#alertaCodigo').html('')
                    }
                }
                
            });

            $('#editarCodigoProductoNuevo').on('keyup', function() {
                for(let x = 0; x < productoIndividual.length; x++){
                    var valorProductoIndividual = productoIndividual[x].split('-');
                    if($(this).val() == valorProductoIndividual[0]){
                        $('#editarAlertaCodigo').html('<label style="color:red;">EL CODIGO '+valorProductoIndividual[0]+' YA EXISTE Y LE PERTENECE A '+valorProductoIndividual[1]+'</label>')
                        
                        break;
                    }else{
                        $('#editarAlertaCodigo').html('')
                    }
                }
                
            });

            $('#crearProducto').on('click', function(e) {
                    let data = $('#frm_producto').serialize();
                    console.log(data);
                    $.confirm({
                        icon: 'fa fa-question',
                        theme: 'modern',
                        animation: 'scale',
                        type: 'blue',
                        title: '¿Está seguro de crear este producto?',
                        content: false,
                        buttons: {
                            Confirmar: function () {
                                $.ajax({
                                    url: '/almacen/producto/crear',
                                    type: 'post',
                                    data: data ,
                                    dataType: 'json',
                                    success: function(response) {
                                        if(response['response'] == true) {
                                            toastr.success('Se grabó satisfactoriamente el producto');
                                            $("#tb_almacen").DataTable().ajax.reload();
                                            $('#precioVentaProducto').val(' ');
                                            $('#codigoProductoNuevo').val(' ');
                                            $('#nombreProductoNuevo').val(' ');
                                            
                                            if($('#cerrarProducto').val() == 1){
                                                $('#productoModal').modal('hide');
                                                if($('#agregandoProducto').val() == 1){
                                                    $('#agregandoProducto').val(0);
                                                    $('#entradaModal').modal('show');
                                                }
                                            }

                                            data = '<option value="'+response['idProducto']+'" >'+response['nombreProducto']+'</option>';
                                            $('.listProductos').append(data);
                                            productos += '<option value="'+response['idProducto']+'" >'+response['nombreProducto']+'</option>';
                                            
                                            productoIndividual[contadorProductoIndividual] = response['codigoProducto']+'-'+response['nombreProducto'];
                                            contadorProductoIndividual = contadorProductoIndividual+1;
                                            // $('.listProductos').load('/almacen/load/productos');
                                        } else {
                                            // toastr.error(response.responseText);
                                            toastr.error('Ocurrio un error al momento de crear este producto porfavor verifique si todos los campos estan correctos');
                                        }
                                    },
                                    error: function(response) {
                                        // toastr.error(response.responseText);
                                        toastr.error('Ocurrio un error al momento de crear este producto porfavor verifique si todos los campos estan correctos');
                                        
                                    }
                                });
                            },
                            Cancelar: function () {
                                
                            }
                        }
                    });
            });

            $('#crearMarca').on('click', function(e) {
                    let data = $('#frm_marca').serialize();
                    console.log(data);
                    $.confirm({
                        icon: 'fa fa-question',
                        theme: 'modern',
                        animation: 'scale',
                        type: 'blue',
                        title: '¿Está seguro de crear esta marca?',
                        content: false,
                        buttons: {
                            Confirmar: function () {
                                $.ajax({
                                    url: '/almacen/marca/crear',
                                    type: 'post',
                                    data: data ,
                                    dataType: 'json',
                                    success: function(response) {
                                        if(response['response'] == true) {
                                            toastr.success('Se grabó satisfactoriamente la marca');
                                            $("#tb_almacen").DataTable().ajax.reload();
                                            $('#nombreMarca').val(' ');
                                            if($('#cerrarMarca').val() == 1){
                                                $('#marcaModal').modal('hide');
                                            }
                                            $('#marcaProducto').load('/almacen/load/marcas');

                                            
                                        } else {
                                            // toastr.error(response.responseText);
                                            toastr.error('Ocurrio un error al momento de crear esta marca porfavor verifique si todos los campos estan correctos');
                                        }
                                    },
                                    error: function(response) {
                                        // toastr.error(response.responseText);
                                        toastr.error('Ocurrio un error al momento de crear esta marca porfavor verifique si todos los campos estan correctos');
                                        
                                    }
                                });
                            },
                            Cancelar: function () {
                                
                            }
                        }
                    });
            });

            $('#crearTipoProducto').on('click', function(e) {
                    let data = $('#frm_producto').serialize();
                    console.log(data);
                    $.confirm({
                        icon: 'fa fa-question',
                        theme: 'modern',
                        animation: 'scale',
                        type: 'blue',
                        title: '¿Está seguro de crear este tipo de producto?',
                        content: 'Recuerda que con esto solo estamos creando un tipo de producto',
                        buttons: {
                            Confirmar: function () {
                                $.ajax({
                                    url: '/almacen/tipo/crear',
                                    type: 'post',
                                    data: data,
                                    dataType: 'json',
                                    success: function(response) {
                                        if(response['response'] == true) {
                                            toastr.success('Se grabó satisfactoriamente el tipo');
                                            $("#tb_almacen").DataTable().ajax.reload();
                                            $('#nuevoTipoProducto').val(' ');
                                            $('#tipoProducto').load('/almacen/load/tiposProductos');

                                            
                                        } else {
                                            // toastr.error(response.responseText);
                                            toastr.error('Ocurrio un error al momento de crear este tipo de producto porfavor verifique si todos los campos estan correctos');
                                        }
                                    },
                                    error: function(response) {
                                        // toastr.error(response.responseText);
                                        toastr.error('Ocurrio un error al momento de crear esta tipo de producto porfavor verifique si todos los campos estan correctos');
                                        
                                    }
                                });
                            },
                            Cancelar: function () {
                                
                            }
                        }
                    });
            });

            $('#crearEntrada').on('click', function(e) {
                let data = $('#frm_entrada').serialize();
                console.log(data);
                $.confirm({
                    icon: 'fa fa-question',
                    theme: 'modern',
                    animation: 'scale',
                    type: 'blue',
                    title: '¿Está seguro de crear esta entrada?',
                    content: false,
                    buttons: {
                        Confirmar: function () {
                            $.ajax({
                                url: '/almacen/entrada/crear',
                                type: 'post',
                                data: data ,
                                dataType: 'json',
                                success: function(response) {
                                    if(response['response'] == true) {
                                        toastr.success('Se grabó satisfactoriamente la entrada');
                                        $("#tb_almacen").DataTable().ajax.reload();
                                        $('.productosAgregados').remove();
                                        $('#cantidadProductos').val('1');
                                        $('#factura').val(' ');
                                        $('#date').val(' ');
                                        $('#precio').val(' ');
                                        $('#cantidad').val(' ');
                                        if($('#cerrar').val() == 1){
                                            $('#entradaModal').modal('hide');
                                        }

                                        
                                    } else {
                                        // toastr.error(response.responseText);
                                        toastr.error('Ocurrio un error al momento de crear esta entrada porfavor fijate si todos los campos estan correctos');
                                    }
                                },
                                error: function(response) {
                                    // toastr.error(response.responseText);
                                    toastr.error('sOcurrio un error al momento de crear esta entrada porfavor fijate si todos los campos estan correctos');
                                    
                                }
                            });
                        },
                        Cancelar: function () {
                            
                        }
                    }
                });
            });

            

    })
</script>
    <script type="text/javascript" src="{{ asset('js/almacen/datatables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/almacen/date.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/almacen/inputFormatMoney.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/almacen/cerrarAutomaticamente.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/almacen/calcularImporte.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/almacen/agregandoDatosModal.js') }}"></script>
    
@endsection
