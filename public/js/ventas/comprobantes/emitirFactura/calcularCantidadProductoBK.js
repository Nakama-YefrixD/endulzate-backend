$('body').on('keyup', '.c_quantity', function() {
    cantidad        = $(this).val()
    precio          = $(this).parent().siblings('.precio').find('input').val();
    cantidaddp      = $('option:selected', $(this).parent().siblings('.producto').find('select')).attr('cantidaddp');
    porcentajedp    = $('option:selected', $(this).parent().siblings('.producto').find('select')).attr('porcentajedp');
    console.log(cantidad)
    console.log(precio)
    console.log(cantidaddp)
    console.log(porcentajedp)
    
    let total           = 0;
    let igvProducto     = 0;
    let subTotal        = 0;

    if(porcentajedp != "0" ){
        
        let vecesDescuento = cantidad/cantidaddp; 
        vecesDescuento = Math.trunc(vecesDescuento);
        // console.log(vecesDescuento)

        let precioPorcentaje = precio * cantidaddp;
        precioPorcentaje = (precioPorcentaje*porcentajedp)/100;
        let descuentoRealizado = vecesDescuento*precioPorcentaje;

        total       = precio * cantidad;
        total       = total - descuentoRealizado;
        igvProducto = (total * 18)/100;
        subTotal    = total - igvProducto;

        $(this).parent().siblings('.descuento').find('.descuento').val(descuentoRealizado);
        $(this).parent().siblings('.descuento').find('span').html(descuentoRealizado);
    }else{
        total       = precio * cantidad;
        igvProducto = (total * 18)/100;
        subTotal    = total - igvProducto;
        $(this).parent().siblings('.descuento').find('.descuento').val(0);
        $(this).parent().siblings('.descuento').find('span').html(0);
    }

    $(this).parent().siblings('.total').find('span').html(total.toFixed(2));
    $(this).parent().siblings('.total').find('input').val(total.toFixed(2));
    $(this).parent().siblings('.subtotal').find('span').html(subTotal.toFixed(2));
    $(this).parent().siblings('.subtotal').find('input').val(subTotal.toFixed(2));
    
    calcularTotalVenta();
});