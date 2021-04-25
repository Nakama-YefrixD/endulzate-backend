$(document).ready(function() {
    var URLactual = window.location.href;
    var ultimateUrl = URLactual.substr(-1,1)
    var x  = URLactual.length
    if(ultimateUrl == '/' || ultimateUrl == '#' ){
        var URLactual = URLactual.substr(0,x-1);
    }
    
    var dt = $('#tb_almacen').DataTable({
                    'searching': false,
                    'processing': true,
                    'serverSide': true,
                    "language": { 'url': "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json" },
                    "ajax": {
                        'url'   : URLactual+"/tb_almacen", 
                        'type'  : 'get',
                        'data'  : function(d) {
                            d.bcodigo = $('#buscar_tb_codigo').val();
                            d.bmarca = $('#buscar_tb_marca').val();
                            d.btipo = $('#buscar_tb_tipo').val();
                            d.bnombre = $('#buscar_tb_nombre').val();
                        },
                    },
                    
                    "columns":[
                        
                        { "data": "codigoProducto" },
                        { "data": "nombreMarca" },
                        { "data": "nombreTipo" },
                        { "data": "nombreProducto" },
                        { "data": "precioProducto" },
                        { "data": "cantidadProducto" },
                        { "data": "idProducto" },
                    ],


                    "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                        var btnEdit = '<button class="btn btn-sm btn-gradient-secondary editar" type="button"><i class="mdi mdi-lead-pencil"></i></button>';
                        var btnDelete = '<button class="btn btn-sm btn-gradient-danger eliminar" type="button"><i class="mdi mdi-delete"></i></button>';
                        $(nRow).find("td:eq(6)").html(btnEdit+" "+btnDelete);

                        
                        $(nRow).find("td:eq(4)").html('S/'+aData['precioProducto']);
                        
                    }
                });
    


    $('#buscar_tb_codigo').on('keyup', function() {
        $("#tb_almacen").DataTable().ajax.reload();
    });

    $('#buscar_tb_marca').on('keyup', function() {
        $("#tb_almacen").DataTable().ajax.reload();
    });

    $('#buscar_tb_tipo').on('keyup', function() {
        $("#tb_almacen").DataTable().ajax.reload();
    });

    $('#buscar_tb_nombre').on('keyup', function() {
        $("#tb_almacen").DataTable().ajax.reload();
    });

    $("#tb_almacen").on('click', '.eliminar', function(){
        var data = dt.row($(this).parents('tr')).data();
        var id = data['idProducto'];
        var token = $("input[name='_token']").val();
        console.log(id);
        $.confirm({
            // icon: 'mdi mdi-delete',
            theme: 'modern',
            // closeIcon: true,
            animation: 'scale',
            type: 'red',
            title:'¿SEGURO DESEA ELIMINAR ESTE PRODUCTO ? ',
            content: 'Los datos eliminados no pueden ser recuperados!',
            buttons: {
                Eliminar: function () {
                    $.ajax({
                            url: "/producto/eliminar",
                            type: 'post',
                            data:{id:id , _token: token},
                            dataType: 'json',
                            success:function(response)
                            {
                                if(response['response'] == true) {
                                    $("#tb_almacen").DataTable().ajax.reload();
                                    toastr.success("El producto se elimino correctamente", "Accion Completada");
                                }else{
                                    toastr.error('Ocurrio un error al momento de eliminar este producto');
                                }
                                
                            },
                            error: function(response) {
                                toastr.error('Ocurrio un error al momento de eliminar este producto');
                            }
                        })
                    
                },
                Cancelar: function () {
                    toastr.warning("Accion Cancelada");                        
                },
                
            }
        });
    }); 


    $("#tb_almacen").on('click', '.editar', function(){
        var data = dt.row($(this).parents('tr')).data();
        let id          = data['idProducto'];
        let codigo      = data['codigoProducto'];
        let nombre      = data['nombreProducto'];
        let precio      = data['precioProducto'];
        let marca       = data['idMarca'];
        let tipo        = data['idTipo'];
        let cantidad    = data['cantidadProducto'];

        $("#editarIdProducto").val(id);
        $("#editarCodigoProductoNuevo").val(codigo);
        $("#editarMarcaProducto").val(marca);
        $("#editarTipoProducto").val(tipo);
        $("#editarNombreProductoNuevo").val(nombre);
        $("#editarPrecioVentaProducto").val(precio);
        $("#editarCantidadProducto").val(cantidad);
        $('#editarMarcaProducto').select2();
        $('#editarTipoProducto').select2();
        $('#editarAlertaCodigo').html('')
        $("#productoEditarModal" ).modal('show');
    }); 

    $('#editarProducto').on('click', function(e) {
        let data = $('#frm_editarProducto').serialize();
        console.log("DATA ENVIADA ");
        console.log(data);
        $.confirm({
            icon: 'fa fa-question',
            theme: 'modern',
            animation: 'scale',
            type: 'blue',
            title: '¿Está seguro de editar este producto?',
            content: false,
            buttons: {
                Confirmar: function () {
                    $.ajax({
                        url: '/almacen/producto/editar',
                        type: 'post',
                        data: data ,
                        dataType: 'json',
                        success: function(response) {
                            if(response['response'] == true) {
                                toastr.success('Se edito el producto satisfactoriamente');
                                $("#tb_almacen").DataTable().ajax.reload();
                                
                            } else {
                                toastr.error('Ocurrio un error al momento de editar este producto porfavor verifique si todos los campos estan correctos');
                            }
                        },
                        error: function(response) {
                            toastr.error('Ocurrio un error al momento de editar este producto porfavor verifique si todos los campos estan correctos');
                            
                        }
                    });
                },
                Cancelar: function () {
                    
                }
            }
        });
    });



})