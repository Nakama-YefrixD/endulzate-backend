@extends('layouts.blank')
@section('title')
    Usuarios
@endsection
@section('content')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Acciones</h4>
            <button type="button" 
                data-toggle="modal" 
                data-target="#usuarioModal" 
                class="btn btn-gradient-primary btn-rounded btn-fw">Agregar Usuario</button>
        </div>
    </div> 
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Usuarios</h4>
            <table id="tb_usuarios" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <!-- <th></th> -->
                        <th>Opciones</th>
                    </tr>
                </thead>
            </table>  
        </div>
    </div>
</div>


<div id="usuarioModal" class="modal fade bd-usuarioModal-modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> Agregar Usuario </h4>
                    <div class="form-group row">
                          <div class="col-sm-6">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="membershipRadios" id="cerrar" value="1" checked="">
                                Cerrar automaticamente
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                          <div class="col-sm-6">
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
                        <form method="post" role="form" data-toggle="validator" id="frm_usuario">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputUsername1">NOMBRE COMPLETOS</label>
                                <input type="text" class="form-control" name="nombreUsuarioCrear" id="nombreUsuarioCrear" placeholder="NOMBRE Y APELLIDOS">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">DIRECCION EMAIL (CON ESTA INFORMACIÓN INICIAMOS SESSIÓN)</label>
                                <input type="email" class="form-control" name="emailUsuarioCrear" id="emailUsuarioCrear" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">CONTRASEÑA</label>
                                <input type="password" class="form-control" name="contrasenaUsuarioCrear" id="contrasenaUsuarioCrear" placeholder="**********">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputConfirmPassword1">REPITA LA CONTRASEÑA</label>
                                <input type="password" class="form-control" id="contrasenaRepetirUsuarioCrear" placeholder="**********">
                            </div>
                            <button type="button" class="btn btn-gradient-primary mr-2" id="crearUsuario">REGISTRAR</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">CANCELAR</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="editarUsuarioModal" class="modal fade bd-editarUsuarioModal-modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> Editar Usuario </h4>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_usuarioEditar" autocomplete="off">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputUsername1">NOMBRE COMPLETOS</label>
                                <input type="text" class="form-control" name= "nombreUsuarioEditar" id="nombreUsuarioEditar" placeholder="NOMBRE Y APELLIDOS">
                                <input type="hidden" name= "datosUsuarioEditar" id="datosUsuarioEditar" value="0" >
                                <input type="hidden" name= "idUsuarioEditar" id="idUsuarioEditar">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">DIRECCION EMAIL (CON ESTA INFORMACIÓN INICIAMOS SESSIÓN)</label>
                                <input type="email" class="form-control" name="emailUsuarioEditar" id="emailUsuarioEditar" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">NUEVA CONTRASEÑA</label>
                                <input type="password" class="form-control" name="contrasenaUsuarioEditar" id="contrasenaUsuarioEditar" placeholder="**********" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputConfirmPassword1">REPITA LA NUEVA CONTRASEÑA</label>
                                <input type="password" class="form-control" id="" placeholder="**********">
                            </div>
                            <button type="button" id="editarUsuarios"               class="btn btn-gradient-primary mr-2">EDITAR</button>
                            <button type="button" id="editarUsuariosCredenciales"   class="btn btn-gradient-warning mr-1">EDITAR CONTRASEÑA</button>
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
    <script type="text/javascript" src="{{ asset('js/Configuraciones/usuarios/datatables.js') }}"></script>

    <script type="text/javascript" src="{{ asset('js/Configuraciones/usuarios/cerrarAutomaticamente.js') }}"></script>



    <script type="text/javascript">
        $('#crearUsuario').on('click', function(e) {
            if($('#contrasenaUsuarioCrear').val().length < 8){
                return toastr.error('La contraseña debe contener mas de 8 caracteres');
            }

            if($('#contrasenaUsuarioCrear').val() == $('#contrasenaRepetirUsuarioCrear').val()){
                

                let data = $('#frm_usuario').serialize();
                console.log(data);
                $.confirm({
                    icon: 'fa fa-question',
                    theme: 'modern',
                    animation: 'scale',
                    type: 'blue',
                    title: '¿Está seguro de crear este usuario?',
                    content: false,
                    buttons: {
                        Confirmar: function () {
                            $.ajax({
                                url: '/configuraciones/usuarios/crear',
                                type: 'post',
                                data: data ,
                                dataType: 'json',
                                success: function(response) {
                                    if(response['response'] == true) {
                                        toastr.success('Se grabó satisfactoriamente el usuario');
                                        $("#tb_usuarios").DataTable().ajax.reload();
                                        $('#nombreUsuarioCrear').val('');
                                        $('#emailUsuarioCrear').val('');
                                        $('#contrasenaUsuarioCrear').val('');
                                        $('#contrasenaRepetirUsuarioCrear').val('');
                                        if($('#cerrar').val() == 1){
                                            $('#usuarioModal').modal('hide');
                                        }
                                    } else {
                                        // toastr.error(response.responseText);
                                        toastr.error('Ocurrio un error al momento de crear este usuario porfavor fijate si todos los campos estan correctos');
                                    }
                                },
                                error: function(response) {
                                    // toastr.error(response.responseText);
                                    toastr.error('Ocurrio un error al momento de crear este usuario porfavor fijate si todos los campos estan correctos');
                                    
                                }
                            });
                        },
                        Cancelar: function () {
                            
                        }
                    }
                });
            }else{
                toastr.error('Las contraseñas deben ser iguales');
            }

            
        });
    </script>   
@endsection
