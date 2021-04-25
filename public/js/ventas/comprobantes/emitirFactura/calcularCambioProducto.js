$('body').on('change','.productos', function() {
    precio          = $('option:selected', this).attr('precio');
    disponible      = $('option:selected', this).attr('disponible');
    cantidaddp      = $('option:selected', this).attr('cantidaddp');
    porcentajedp    = $('option:selected', this).attr('porcentajedp');

    

    cantidad            = $(this).parent().siblings('.cantidad').find('input').val();
    let total           = 0;
    let igvProducto     = 0;
    let subTotal        = 0;

    if(porcentajedp != '' || porcentajedp != '0'){
        let vecesDescuento = cantidad/cantidaddp; 
        vecesDescuento = Math.trunc(vecesDescuento);
        console.log(vecesDescuento)

        let precioPorcentaje = precio * cantidaddp;
        precioPorcentaje = (precioPorcentaje*porcentajedp)/100;
        let descuentoRealizado = vecesDescuento*precioPorcentaje;

        total       = precio * cantidad;
        total       = total - descuentoRealizado;
        igvProducto = (total * 18)/100;
        subTotal    = total - igvProducto;

        $(this).parent().siblings('.descuento').find('span').html(descuentoRealizado);
        $(this).parent().siblings('.descuento').find('.descuento').val(descuentoRealizado);
        $(this).parent().siblings('.descuento').find('.cantidaddp').val(cantidaddp);
        $(this).parent().siblings('.descuento').find('.porcentajedp').val(porcentajedp);
    }else{
        total       = precio * cantidad;
        igvProducto = (total * 18)/100;
        subTotal    = total - igvProducto;
        $(this).parent().siblings('.descuento').find('span').html(0);
        $(this).parent().siblings('.descuento').find('.descuento').val(0);
        $(this).parent().siblings('.descuento').find('.cantidaddp').val(0);
        $(this).parent().siblings('.descuento').find('.porcentajedp').val(0);
    }


    $(this).parent().siblings('.disponible').find('input').val(disponible);
    $(this).parent().siblings('.disponible').find('span').html(disponible);

    $(this).parent().siblings('.precio').find('input').val(precio);
    $(this).parent().siblings('.precio').find('span').html(precio);

    $(this).parent().siblings('.total').find('input').val(total.toFixed(2));
    $(this).parent().siblings('.total').find('span').html(total.toFixed(2));

    $(this).parent().siblings('.subtotal').find('input').val(subTotal.toFixed(2));
    $(this).parent().siblings('.subtotal').find('span').html(subTotal.toFixed(2));
    
    calcularTotalVenta();
});