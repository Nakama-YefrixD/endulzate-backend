@extends('layouts.blank')
@section('title')
    Entradas
@endsection
@section('content')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Modulos de agregar</h4>
            <button type="button" data-toggle="modal" data-target="#entradaModal" class="btn btn-gradient-primary btn-rounded btn-fw">Agregar Entrada</button>
        </div>
    </div> 
</div>


<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Registro de entradas</h4>
            <table id="tb_entradas" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Numero</th>
                        <th>RUC proveedor</th>
                        <th>Proveedor</th>
                        <th>Fecha</th>
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



<div id="entradaDetalladaModal" class="modal fade bd-entradaDetalladaModal-lg" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> Detalles de la entrada </h4>
                </div>
                <div class="modal-body">
                    <div class="card-body" id="entradaDetalladaModalBody">
                    <label>Proveedor:</label><br>
                    <span id="proveedorEntradaDetalle"></span>
                    <br><label>Numero entrada:</label><br>
                    <span id="numeroEntradaDetalle"></span>
                    <br><label>Fecha de emisón:</label><br>
                    <span id="fechaEntradaDetalle"></span><br><br>
                    <table class="table table-bordered" id="tablaDetallesEntradaModal">
                    <thead>
                      <tr>
                        <th>
                          #
                        </th>
                        <th>
                          Producto
                        </th>
                        <th>
                          Precio
                        </th>
                        <th>
                          Cantidad
                        </th>
                        <th>
                          Importe
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      
                    </tbody>
                  </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@section('script')


<!-- DATATABLES -->
<script type="text/javascript" src="{{ asset('js/almacen/Entradas/datatables.js') }}"></script>

<script type="text/javascript">
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
                                $("#tb_entradas").DataTable().ajax.reload();
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

</script>
<script type="text/javascript" src="{{ asset('js/almacen/select2.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/almacen/inputFormatMoney.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/almacen/calcularImporte.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/almacen/date.js') }}"></script>
@endsection
