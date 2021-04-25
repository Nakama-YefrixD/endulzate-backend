$('body').on('keyup', '.descuento', function() {
    cantidad    = $(this).parent().siblings('.cantidad').find('.c_quantity').val();
    precio      = $(this).parent().siblings('.precio').find('input').val();
    descuento   = $(this).val();
    
    let total           = 0;
    let igvProducto     = 0;
    let subTotal        = 0;

    precio = precio - descuento;
    total       = precio * cantidad;
    subTotal    = (total / 1.18);
    igvProducto = total - subTotal;

    $(this).parent().siblings('.total').find('span').html(total.toFixed(2));
    $(this).parent().siblings('.total').find('input').val(total.toFixed(2));
    $(this).parent().siblings('.subtotal').find('span').html(subTotal.toFixed(2));
    $(this).parent().siblings('.subtotal').find('input').val(subTotal.toFixed(2));
    
    calcularTotalVenta();
});