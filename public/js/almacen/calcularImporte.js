$(document).ready(function() {
    $('body').on('keyup', '.cantidadProductoEntrada', function() {
        let precio = $(this).parent().siblings('.precioCompraContainer').find('.precioCompra').val();
        console.log(precio)
        var result = precio.split('S/');
        let importe = ($(this).val() * result[1]);
        $(this).parent().siblings('.importeCompraContainer').find('input').val(importe);
        formatCurrency($(this).parent().siblings('.importeCompraContainer').find('input'));
        formatCurrency($(this).parent().siblings('.importeCompraContainer').find('input'), "blur");
    });

    $('body').on('keyup', '.precioCompra', function() {
        let cantidad = $(this).parent().siblings('.cantidadCompraContainer').find('input').val();
        console.log(cantidad)
        var result = $(this).val().split('S/');
        let importe = (cantidad * result[1]);
        
        $(this).parent().siblings('.importeCompraContainer').find('input').val(importe);
        formatCurrency($(this).parent().siblings('.importeCompraContainer').find('input'));
        formatCurrency($(this).parent().siblings('.importeCompraContainer').find('input'), "blur");
    });

    
})