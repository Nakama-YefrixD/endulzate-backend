$(document).ready(function() {
    var URLactual = window.location.href;
    var ultimateUrl = URLactual.substr(-1,1)
    var x  = URLactual.length
    if(ultimateUrl == '/' || ultimateUrl == '#' ){
        var URLactual = URLactual.substr(0,x-1);
    }
    
    var dt = $('#tb_proveedores').DataTable({
                "processing": false,
                "serverSide": true,
                "language": { 'url': "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json" },
                "ajax": URLactual+"/tb_proveedores", 
                "columns":[
                    
                    { "data": "ruc" },
                    { "data": "nombre" },
                    { "data": "numero" },
                    { "data": "direccion" },
                    { "data": "tipo" },
                    { "data": "id" },
                ],
                "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                    var btnEdit = '<button class="btn btn-sm btn-gradient-secondary editar" type="button"><i class="mdi mdi-lead-pencil"></i></button>';
                    $(nRow).find("td:eq(5)").html(btnEdit);
                }
            });

$("#tb_proveedores").on('click', '.editar', function(){
    var data = dt.row($(this).parents('tr')).data();
    let id = data['id'];
    let nombre = data['nombre'];
    let ruc = data['ruc'];
    let numero = data['numero'];
    let direccion = data['direccion'];
    let tipo = data['tipo'];

    $("#editarIdProveedor").val(id);
    $("#editarRucProveedor").val(ruc);
    $("#editarTelefonoProveedor").val(numero);
    $("#editarNombreProveedor").val(nombre);
    $("#editarDireccionProveedor").val(direccion);
    $("#editarProveedorModal" ).modal('show');
}); 


  $('#editarProveedor').on('click', function(e) {
        let data = $('#frm_editarProveedor').serialize();
        console.log(data);
        $.confirm({
            icon: 'fa fa-question',
            theme: 'modern',
            animation: 'scale',
            type: 'blue',
            title: '¿Está seguro de editar este proveedor?',
            content: false,
            buttons: {
                Confirmar: function () {
                    $.ajax({
                        url: '/almacen/proveedores/editar',
                        type: 'post',
                        data: data ,
                        dataType: 'json',
                        success: function(response) {
                            if(response['response'] == true) {
                                toastr.success('Se edito el proveedor satisfactoriamente');
                                $("#tb_proveedores").DataTable().ajax.reload();
                                
                            } else {
                                toastr.error('Ocurrio un error al momento de editar este proveedor porfavor verifique si todos los campos estan correctos');
                            }
                        },
                        error: function(response) {
                            toastr.error('Ocurrio un error al momento de editar este proveedor porfavor verifique si todos los campos estan correctos');
                            
                        }
                    });
                },
                Cancelar: function () {
                    
                }
            }
        });
    });
})