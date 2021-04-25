@extends('layouts.blank')
@section('title')
    Marcas
@endsection
@section('content')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Modulos de agregar</h4>
            <button type="button" data-toggle="modal" data-target="#marcaModal" class="btn btn-gradient-info btn-rounded btn-fw">Agregar Marca</button>
        </div>
    </div> 
</div>


<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Marcas</h4>
            <table id="tb_marcas" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Nombre de la marca</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
            </table>  
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



<div id="editarMarcaModal" class="modal fade bd-editarMarcaModal" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Editar marca </h4>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_editarMarca">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12">
                                        <label>Nombre de la marca</label>
                                        <input type="text" class="form-control" name="editarNombreMarca" id="editarNombreMarca" >
                                        <input type="hidden" name="editarIdMarca" id="editarIdMarca" >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group boton">
                                <button type="button" class="addexis form-control btn btn-block btn-success btn-lg" id="editarMarca">
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
<script type="text/javascript" src="{{ asset('js/almacen/Marcas/datatables.js') }}"></script>


<script type="text/javascript">
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
                                    $("#tb_marcas").DataTable().ajax.reload();
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
</script>
@endsection
