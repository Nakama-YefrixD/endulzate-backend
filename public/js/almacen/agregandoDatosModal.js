$(document).ready(function() {
    $('body').on('click', '#agregarNuevoProducto', function() {
        $('#agregandoProducto').val(1);
        $('#entradaModal').modal('hide');
        $('#productoModal').modal('show');
    });
    
    $('body').on('click', '#agregarNuevoProveedor', function() {
        $('#agregandoProveedor').val(1);
        $('#entradaModal').modal('hide');
        $('#proveedorModal').modal('show');
    });
})