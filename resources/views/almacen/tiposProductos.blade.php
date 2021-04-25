@extends('layouts.blank')
@section('title')
    Tipos de productos
@endsection
@section('content')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Modulos de agregar</h4>
            <button type="button" data-toggle="modal" data-target="#tipoProductoModal" class="btn btn-gradient-warning btn-rounded btn-fw">Agregar Tipo de Producto</button>
        </div>
    </div> 
</div>


<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Tipos de productos</h4>
            <table id="tb_tiposProductos" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Tipo de producto</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
            </table>  
        </div>
    </div>
</div>


<div id="editarTipoProductoModal" class="modal fade bd-editarTipoProductoModal" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> Editar tipo de producto </h4>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_editarTipoProducto">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12">
                                        <label>Nombre del tipo de producto</label>
                                        <input type="text" class="form-control" name="editarNombreTipoProducto" id="editarNombreTipoProducto" >
                                        <input type="hidden" name="editarIdTipoProducto" id="editarIdTipoProducto" >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group boton">
                                <button type="button" class="addexis form-control btn btn-block btn-success btn-lg" id="editarTipoProducto">
                                    Editar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="tipoProductoModal" class="modal fade bd-tipoProductoModal" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> Agregar tipo de producto </h4>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_tipoProducto">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12">
                                        <label>Nombre del tipo de producto</label>
                                        <input type="text" class="form-control" name="nuevoTipoProducto" id="nuevoTipoProducto" >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group boton">
                                <button type="button" class="addexis form-control btn btn-block btn-success btn-lg" id="crearTipoProducto">
                                    CREAR</button>
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
<!-- DATATABLES -->
<script type="text/javascript" src="{{ asset('js/almacen/TiposProductos/datatables.js') }}"></script>
<script type="text/javascript">
    $('#crearTipoProducto').on('click', function(e) {
        let data = $('#frm_tipoProducto').serialize();
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
                                $("#tb_tiposProductos").DataTable().ajax.reload();
                                $('#nuevoTipoProducto').val('');
                                $('#tipoProductoModal').modal('hide')
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
</script>

@endsection
