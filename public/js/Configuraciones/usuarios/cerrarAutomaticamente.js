$(document).ready(function() {
    $('body').on('click', '#cerrar', function() {
        $('#cerrar').val('1');
        $('#abrir').val('0');
    });
    
    $('body').on('click', '#abrir', function() {
        $('#cerrar').val('0');
        $('#abrir').val('1');
    });

})