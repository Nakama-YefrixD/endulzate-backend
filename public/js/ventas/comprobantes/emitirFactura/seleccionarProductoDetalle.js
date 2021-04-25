$("#tb_buscarProducto").on('click', '.seleccionarProductoDetalle', function(){
    var dataTabla       = dt.row($(this).parents('tr')).data();
    let idProducto      = dataTabla['idProducto'];
    let codigo          = dataTabla['codigoProducto'];
    let nombre          = dataTabla['nombreProducto'];
        nombre          = codigo+" - "+nombre;
    let disponible      = dataTabla['disponiblesProducto'];
    let precio          = dataTabla['precioProducto'];
    let porcentajedp    = dataTabla['porcentajeProductoDescuento'];
    let cantidaddp      = dataTabla['cantidadProductoDescuento'];
    console.log(porcentajedp)
    console.log(cantidaddp)
    if(porcentajedp == null){
        porcentajedp = '0';
        cantidaddp   = '0';
    }
    console.log(porcentajedp)
    console.log(cantidaddp)

    let option =    '<option precio     =   "'+precio+'"';
        option +=   'disponible         =   "'+disponible+'"';
        option +=   'porcentajedp       =   "'+porcentajedp+'"';
        option +=   'cantidaddp         =   "'+cantidaddp+'"';
        option +=   'value              =   "'+idProducto+'">'+nombre+'</option>';


    let data = '<tr>';
        data += '<td class="producto"><select class="form-control productos" name="nombreProducto[]" id="nombreProducto[] " style="width: 100%;">';
        data += option;
        data += '</select></td>';
        data += '<td class="cantidad"><input type="text" class="form-control c_quantity" name="cantidad[]" value="">';
        data += '</td>';
        data += '<td class="disponible"><span>'+disponible+'</span><input type="hidden" class="form-control disponible" name="disponible[]" value="'+disponible+'" readonly="">';
        data += '</td>';
        data += '<td class="precio"><span>'+precio+'</span><input type="hidden" class="form-control precio" name="precio[]" value="'+precio+'" readonly="">';
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

        $('#buscar_tb_codigo').val("");
        $("#buscar_tb_marca").val("");
        $("#buscar_tb_tipo").val("");
        $("#buscar_tb_nombre").val("");
        $("#buscar_tb_precio").val("");

    $('#tb_products tbody').append(data);
    // $('.productos').select2();

    calcularTotalVenta();
    
    $('#agregarProductoDetalleModal').modal('hide');
    // if(isset($('#tb_products').DataTable())) { // CLEAR DATATABLE
        
    //     $('#tb_products').DataTable({
    //         "scrollX": true,
    //         'searching': false,
    //     });
    //     $('.dataTables_length').addClass('bs-select');
    // }
    
})
