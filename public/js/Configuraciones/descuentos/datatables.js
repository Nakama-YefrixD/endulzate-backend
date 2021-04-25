$(document).ready(function() {
    var URLactual = window.location.href;
    var ultimateUrl = URLactual.substr(-1,1)
    var x  = URLactual.length
    if(ultimateUrl == '/' || ultimateUrl == '#' ){
        var URLactual = URLactual.substr(0,x-1);
    }
    
    var dt = $('#tb_descuentos').DataTable({
                "processing": false,
                "serverSide": true,
                "language": { 'url': "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json" },
                "ajax": URLactual+"/tb_descuentos", 
                "columns":[
                    { "data": "idDescuentos" },
                    { "data": "codigoProductos" },
                    { "data": "nombreProductos" },
                    { "data": "cantidadDescuentos" },
                    { "data": "porcentajeDescuentos" },
                    { "data": "idDescuentos" },
                    
                ],
                "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                    var btnEdit = '<button class="btn btn-sm btn-gradient-secondary editar" type="button"><i class="mdi mdi-lead-pencil"></i></button>';
                    var btnPrueba = '<button class="btn btn-sm btn-gradient-info prueba" type="button"><i class="mdi mdi-book-open"></i></button>';
                    $(nRow).find("td:eq(5)").html(btnEdit+ ' '+btnPrueba);
                }
            });

    $("#tb_descuentos").on('click', '.prueba', function(){
        var data = dt.row($(this).parents('tr')).data();
        let id = data['idDescuentos'];
        let producto = data['nombreProductos'];
        let precio = data['precioProductos'];
        let cantidad = data['cantidadDescuentos'];
        let porcentaje = data['porcentajeDescuentos'];

        $("#productoDescuentoPrueba").html(producto);
        $("#precioProductoPrueba").html(precio);
        $("#cantidadDescuentoPrueba").html(cantidad);
        $("#porcentajeDescuentoPrueba").html(porcentaje);
        $("#pruebaDescuentoModal" ).modal('show');
    });


    $("#tb_descuentos").on('click', '.editar', function(){
        var data = dt.row($(this).parents('tr')).data();
        let id          = data['idDescuentos'];
        let idProducto  = data['idProductos'];
        let porcentaje  = data['porcentajeDescuentos'];
        let cantidad    = data['cantidadDescuentos'];

        $("#idEditar").val(id);
        $("#codigoProductoEditar").val(idProducto);
        $("#porcentajeEditar").val(porcentaje);
        $("#cantidadEditar").val(cantidad);
        $('#codigoProductoEditar').select2();
        $("#editarDescuentoModal").modal('show');
    }); 

    $('#descuentoEditar').on('click', function(e) {
        let data = $('#frm_descuentoEditar').serialize();
        console.log(data);
        $.confirm({
            icon: 'fa fa-question',
            theme: 'modern',
            animation: 'scale',
            type: 'blue',
            title: '¿Está seguro de editar este descuento?',
            content: 'Las ventas que se hicieron antes de editar este descuento seguiran con el descuento anterior.',
            buttons: {
                Confirmar: function () {
                    $.ajax({
                        url: '/configuraciones/descuentos/editar',
                        type: 'post',
                        data: data ,
                        dataType: 'json',
                        success: function(response) {
                            if(response['response'] == true) {
                                toastr.success('Se edito el descuento satisfactoriamente');
                                $("#tb_descuentos").DataTable().ajax.reload();
                                
                            } else {
                                toastr.error('Ocurrio un error al momento de editar este descuento porfavor verifique si todos los campos estan correctos');
                            }
                        },
                        error: function(response) {
                            toastr.error('Ocurrio un error al momento de editar este descuento porfavor verifique si todos los campos estan correctos');
                            
                        }
                    });
                },
                Cancelar: function () {
                    
                }
            }
        });
    });
})