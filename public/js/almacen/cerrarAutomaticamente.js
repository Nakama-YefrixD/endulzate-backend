$(document).ready(function() {
    $('body').on('click', '#cerrar', function() {
        $('#cerrar').val('1');
        $('#abrir').val('0');
    });
    
    $('body').on('click', '#abrir', function() {
        $('#cerrar').val('0');
        $('#abrir').val('1');
    });


    $('body').on('click', '#cerrarProveedor', function() {
        $('#cerrarProveedor').val('1');
        $('#abrirProveedor').val('0');
    });
    
    $('body').on('click', '#abrirProveedor', function() {
        $('#cerrarProveedor').val('0');
        $('#abrirProveedor').val('1');
    });

    $('body').on('click', '#cerrarMarca', function() {
        $('#cerrarMarca').val('1');
        $('#abrirMarca').val('0');
    });
    
    $('body').on('click', '#abrirMarca', function() {
        $('#cerrarMarca').val('0');
        $('#abrirMarca').val('1');
    });

    $('body').on('click', '#cerrarProducto', function() {
        $('#cerrarProducto').val('1');
        $('#abrirProducto').val('0');
    });
    
    $('body').on('click', '#abrirProducto', function() {
        $('#cerrarProducto').val('0');
        $('#abrirProducto').val('1');
    });

})