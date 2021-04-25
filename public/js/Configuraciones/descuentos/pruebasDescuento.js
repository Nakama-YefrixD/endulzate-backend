$('#cantidadPrueba').on('keyup', function() {
    let cantidadPrueba = $(this).val();
    let precio = $('#precioProductoPrueba').text();
    let porcentaje = $('#porcentajeDescuentoPrueba').text();
    let cantidad = $('#cantidadDescuentoPrueba').text();

    let importesd = precio * cantidadPrueba;

    //LA CANTIDAD DE VECES QUE SE APLICA EL DESCUENTO
    let vecesDescuento = cantidadPrueba/cantidad; 
    vecesDescuento = Math.trunc(vecesDescuento);

    // OBTENER EL PRECIO DEL DESCUENTO (A CUANTO EQUIVALE EL PORCENTAJE DE DESCUENTO)
    let precioPorcentaje = precio * cantidad;
    precioPorcentaje = precioPorcentaje*porcentaje;
    precioPorcentaje = precioPorcentaje/100;
    
    let descuentoRealizado = vecesDescuento*precioPorcentaje;
    let importecd = importesd - descuentoRealizado;

    $('#importeSDPrueba').text(importesd.toFixed(2));
    $('#importeCDPrueba').text(importecd.toFixed(2));
    $('#totalDescuentoPrueba').text(descuentoRealizado.toFixed(2));
    $('#vecesDescuentoPrueba').text(vecesDescuento);
    

});