$(document).ready(function() {
    var URLactual = window.location.href;
    var ultimateUrl = URLactual.substr(-1,1)
    var x  = URLactual.length
    if(ultimateUrl == '/' || ultimateUrl == '#' ){
        var URLactual = URLactual.substr(0,x-1);
    }
    
    var dt = $('#tb_tiposProductos').DataTable({
                "processing": false,
                "serverSide": true,
                "language": { 'url': "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json" },
                "ajax": URLactual+"/tb_tiposProductos", 
                "columns":[
                    { "data": "nombre" },
                    { "data": "id" },
                ],
                "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                    var btnEdit = '<button class="btn btn-sm btn-gradient-secondary editar" type="button"><i class="mdi mdi-lead-pencil"></i></button>';
                    var btnDelete = '<button class="btn btn-sm btn-gradient-danger eliminar" type="button"><i class="mdi mdi-delete"></i></button>';
                    $(nRow).find("td:eq(1)").html(btnEdit+" "+btnDelete);
                }
            });

    $("#tb_tiposProductos").on('click', '.editar', function(){
        var data = dt.row($(this).parents('tr')).data();
        let id = data['id'];
        let nombre = data['nombre'];

        $("#editarIdTipoProducto").val(id);
        $("#editarNombreTipoProducto").val(nombre);
        $("#editarTipoProductoModal" ).modal('show');
    }); 


    $('#editarTipoProducto').on('click', function(e) {
        let data = $('#frm_editarTipoProducto').serialize();
        console.log(data);
        $.confirm({
            icon: 'fa fa-question',
            theme: 'modern',
            animation: 'scale',
            type: 'blue',
            title: '¿Está seguro de editar este tipo de producto?',
            content: 'Recuerda que todos los productos que tengan este tipo asignado cambiaran exponencialmente.',
            buttons: {
                Confirmar: function () {
                    $.ajax({
                        url: '/almacen/tiposproductos/editar',
                        type: 'post',
                        data: data ,
                        dataType: 'json',
                        success: function(response) {
                            if(response['response'] == true) {
                                toastr.success('Se edito el tipo de producto satisfactoriamente');
                                $("#tb_tiposProductos").DataTable().ajax.reload();
                                
                            } else {
                                toastr.error('Ocurrio un error al momento de editar este tipo de producto porfavor verifique si todos los campos estan correctos');
                            }
                        },
                        error: function(response) {
                            toastr.error('Ocurrio un error al momento de editar este tipo de producto porfavor verifique si todos los campos estan correctos');
                            
                        }
                    });
                },
                Cancelar: function () {
                    
                }
            }
        });
    });


    $("#tb_tiposProductos").on('click', '.eliminar', function(){
        var data = dt.row($(this).parents('tr')).data();
        var id = data['id'];
        var token = $("input[name='_token']").val();
        console.log(id+token);
        $.confirm({
            // icon: 'mdi mdi-delete',
            theme: 'modern',
            // closeIcon: true,
            animation: 'scale',
            type: 'red',
            title:'¿SEGURO DESEA ELIMINAR ESTE TIPO DE PRODUCTO ? ',
            content: 'Los datos eliminados no pueden ser recuperados!',
            buttons: {
                Eliminar: function () {
                    $.ajax({
                            url: "/almacen/tiposproductos/eliminar",
                            type: 'post',
                            data:{id:id , _token: token},
                            dataType: 'json',
                            success:function(response)
                            {
                                if(response['response'] == true) {
                                    $("#tb_tiposProductos").DataTable().ajax.reload();
                                    toastr.success("El tipo de producto se elimino correctamente", "Accion Completada");
                                }else{
                                    toastr.error('Debido a que este tipo de producto ya esta asignado a un producto','El tipo de producto no se pudo eliminar');
                                }
                                
                            },
                            error: function(response) {
                                toastr.error('Debido a que este tipo de producto ya esta asignado a un producto','El tipo de producto no se pudo eliminar');
                            }
                        })
                    
                },
                Cancelar: function () {
                    toastr.warning("Accion Cancelada");                        
                },
                
            }
        });
    }); 
})