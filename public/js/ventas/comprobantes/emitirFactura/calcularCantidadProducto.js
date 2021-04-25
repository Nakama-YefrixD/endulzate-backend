$('body').on('keyup', '.c_quantity', function() {
    cantidad        = $(this).val()
    precio          = $(this).parent().siblings('.precio').find('input').val();
    // cantidaddp      = $('option:selected', $(this).parent().siblings('.producto').find('select')).attr('cantidaddp');
    // porcentajedp    = $('option:selected', $(this).parent().siblings('.producto').find('select')).attr('porcentajedp');
    descuento       = $(this).parent().siblings('.descuento').find('.descuento').val();
    
    let total           = 0;
    let igvProducto     = 0;
    let subTotal        = 0;

    precio = precio - descuento;
    total       = precio * cantidad;
    subTotal    = (total / 1.18);
    igvProducto = total - subTotal;

    
    // $(this).parent().siblings('.descuento').find('.descuento').val(0);
    // $(this).parent().siblings('.descuento').find('span').html(0);

    $(this).parent().siblings('.total').find('span').html(total.toFixed(2));
    $(this).parent().siblings('.total').find('input').val(total.toFixed(2));
    $(this).parent().siblings('.subtotal').find('span').html(subTotal.toFixed(2));
    $(this).parent().siblings('.subtotal').find('input').val(subTotal.toFixed(2));
    
    calcularTotalVenta();
});