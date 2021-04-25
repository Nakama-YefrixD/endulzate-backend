$(document).ready(function() {
    var URLactual = window.location.href;
    var ultimateUrl = URLactual.substr(-1,1)
    var x  = URLactual.length
    if(ultimateUrl == '/' || ultimateUrl == '#' ){
        var URLactual = URLactual.substr(0,x-1);
    }
    
    var dt = $('#tb_usuarios').DataTable({
                "processing": false,
                "serverSide": true,
                "language": { 'url': "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json" },
                "ajax": URLactual+"/tb_usuarios", 
                "columns":[
                    { "data": "idUsuario" },
                    { "data": "nombreUsuario" },
                    { "data": "emailUsuario" },
                    { "data": "idUsuario" },
                    
                ],
                "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                    var btnEdit = '<button class="btn btn-sm btn-gradient-secondary editar" type="button"><i class="mdi mdi-lead-pencil"></i></button>';
                    $(nRow).find("td:eq(3)").html(btnEdit);
                }
            });

    $("#tb_usuarios").on('click', '.editar', function(){
        var data = dt.row($(this).parents('tr')).data();
        let id = data['idUsuario'];
        let nombre = data['nombreUsuario'];
        let email = data['emailUsuario'];

        $("#idUsuarioEditar").val(id);
        $("#nombreUsuarioEditar").val(nombre);
        $("#emailUsuarioEditar").val(email);
        $("#editarUsuarioModal" ).modal('show');
    }); 


    $('#editarUsuarios').on('click', function(e) {
        $("#datosUsuarioEditar").val("0");
        let data = $('#frm_usuarioEditar').serialize();
        console.log(data);
        editarUsuario(data);
    });

    $('#editarUsuariosCredenciales').on('click', function(e) {
        $("#datosUsuarioEditar").val("1");
        let data = $('#frm_usuarioEditar').serialize();
        console.log(data);
        editarUsuario(data);
    });

    function editarUsuario(data){
        $.confirm({
            icon: 'fa fa-question',
            theme: 'modern',
            animation: 'scale',
            type: 'blue',
            title: '¿Está seguro de editar esta usuario?',
            content: '',
            buttons: {
                Confirmar: function () {
                    $.ajax({
                        url: '/configuraciones/usuarios/editar',
                        type: 'post',
                        data: data ,
                        dataType: 'json',
                        success: function(response) {
                            if(response['response'] == true) {
                                toastr.success('Se edito el usuario satisfactoriamente');
                                $("#tb_usuarios").DataTable().ajax.reload();
                                $('#editarUsuarioModal').modal('hide');
                            } else {
                                toastr.error('Ocurrio un error al momento de editar esta usuario porfavor verifique si todos los campos estan correctos');
                            }
                        },
                        error: function(response) {
                            toastr.error('Ocurrio un error al momento de editar esta usuario porfavor verifique si todos los campos estan correctos');
                        }
                    });
                },
                Cancelar: function () {
                    
                }
            }
        });
    }

})