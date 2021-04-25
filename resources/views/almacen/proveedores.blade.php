@extends('layouts.blank')
@section('title')
    Proveedores
@endsection
@section('content')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Modulos de agregar</h4>
            <button type="button" data-toggle="modal" data-target="#proveedorModal" class="btn btn-gradient-warning btn-rounded btn-fw">Agregar Proveedor</button>
        </div>
    </div> 
</div>


<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Registro de proveedores</h4>
            <table id="tb_proveedores" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>RUC proveedor</th>
                        <th>Proveedor</th>
                        <th>Telefóno</th>
                        <th>Dirección</th>
                        <th>Tipo</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
            </table>  
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





<div id="editarProveedorModal" class="modal fade bd-editarProveedorModal" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> Editar Proveedor </h4>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_editarProveedor">
                            @csrf
                            <div class="form-group" >
                                <div class="row">
                                    <div class="col-6">
                                        <label>RUC:</label>
                                        <input type="hidden" name="editarIdProveedor" id="editarIdProveedor">
                                        <input type="text" class="form-control" name="editarRucProveedor" id="editarRucProveedor">
                                    </div>
                                    <div class="col-6">
                                        <label>Telefóno:</label>
                                        <input type="text" class="form-control" name="editarTelefonoProveedor" id="editarTelefonoProveedor">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12">
                                        <label>Nombre del proveedor:</label>
                                        <textarea class="form-control" name="editarNombreProveedor" id="editarNombreProveedor"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12">
                                        <label>Dirección:</label>
                                        <textarea class="form-control" name="editarDireccionProveedor" id="editarDireccionProveedor"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group boton">
                                <button type="button" class="addexis form-control btn btn-block btn-success btn-lg" id="editarProveedor">
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
<!-- DATATABLES -->
<script type="text/javascript" src="{{ asset('js/almacen/Proveedores/datatables.js') }}"></script>


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
                                    $("#tb_proveedores").DataTable().ajax.reload();
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
    })
</script>

@endsection
