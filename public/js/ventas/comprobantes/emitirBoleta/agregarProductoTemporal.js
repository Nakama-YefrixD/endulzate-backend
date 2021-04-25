$('#btnAddProductoTemporal').on('click', function() {
    $('#agregarProductoDetalleModal').modal('hide');
    $('#productoTemporalModal').modal('show');
});

$('#crearProducto').on('click', function(e) {
    let data = $('#frm_producto').serialize();
    console.log(data);
    $.confirm({
        icon: 'fa fa-question',
        theme: 'modern',
        animation: 'scale',
        type: 'blue',
        title: '¿Está seguro de crear este producto temporal?',
        content: false,
        buttons: {
            Confirmar: function () {
                $.ajax({
                    url: '/ventas/crear/productoTemporal',
                    type: 'post',
                    data: data ,
                    dataType: 'json',
                    success: function(response) {
                        if(response['response'] == true) {

                            let idProducto     = response['idProducto'];
                            let nombreProducto = response['nombreProducto'];
                            let codigoProducto = response['codigoProducto'];
                            let precioProducto = response['precioProducto'];
                            nombreProducto = codigoProducto+" - "+nombreProducto;
                            let option =    '<option precio     =   "'+precioProducto+'"';
                                option +=   'disponible         =   "0"';
                                option +=   'porcentajedp       =   "0"';
                                option +=   'cantidaddp         =   "0"';
                                option +=   'value              =   "'+idProducto+'">'+nombreProducto+'</option>';


                            let data = '<tr>';
                                data += '<td class="producto"><select class="form-control productos" name="nombreProducto[]" id="nombreProducto[] " style="width: 100%;">';
                                data += option;
                                data += '</select></td>';
                                data += '<td class="cantidad"><input type="text" class="form-control c_quantity" name="cantidad[]" value="">';
                                data += '</td>';
                                data += '<td class="disponible"><span>0</span><input type="hidden" class="form-control disponible" name="disponible[]" value="0" readonly="">';
                                data += '</td>';
                                data += '<td class="precio"><span>'+precioProducto+'</span><input type="hidden" class="form-control precio" name="precio[]" value="'+precioProducto+'" readonly="">';
                                data += '</td>';
                                data += '<td class="descuento"><input type="number" class="form-control descuento" name="descuento[]" value="0">';
                                data += '<input type="hidden" class="form-control cantidaddp" name="cantidaddp[]" value="0" readonly="">';
                                data += '<input type="hidden" class="form-control porcentajedp" name="porcentajedp[]" value="0" readonly="">';
                                data += '</td>';
                                data += '<td class="subtotal"><span>0</span><input type="hidden" class="form-control subtotal" name="subtotal[]" value="0" readonly="" style="width: 100px;">';
                                data += '</td>';
                                data += '<td class="total"><span>0</span><input type="hidden" class="form-control total" name="total[]" value="0" readonly="" style="width: 100px;">';
                                data += '</td>';

                                data += '<td>';
                                    data += '<button type="button" class="btn btn-gradient-danger btn-rounded btn-icon remove"><i class="mdi mdi-close"></i></button>';
                                data += '</td>';

                                data += '</tr>';

                            $('#tb_products tbody').append(data);
                            $('#productoTemporalModal').modal('hide');

                            $('#codigoProductoNuevo').val("");
                            $('#nombreProductoNuevo').val("");
                            $('#precioVentaProducto').val("");

                        } else {
                            toastr.error('Ocurrio un error al momento de crear este producto porfavor verifique si todos los campos estan correctos');
                        }
                    },
                    error: function(response) {
                        toastr.error('Ocurrio un error al momento de crear este producto porfavor verifique si todos los campos estan correctos');
                        
                    }
                });
            },
            Cancelar: function () {
                
            }
        }
    });
});