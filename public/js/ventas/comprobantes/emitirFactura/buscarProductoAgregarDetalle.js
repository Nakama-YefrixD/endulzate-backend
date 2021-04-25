var dt = $('#tb_buscarProducto').DataTable({
    "processing": true,
    'searching': false,
    "serverSide": true,
    "language": { 'url': "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json" },
    "ajax": {
        'url':"/ventas/tb_buscarProducto",
        'type' : 'get',
        'data': function(d) {
            d.bcodigo   = $('#buscar_tb_codigo').val();
            d.bmarca    = $('#buscar_tb_marca').val();
            d.btipo     = $('#buscar_tb_tipo').val();
            d.bnombre   = $('#buscar_tb_nombre').val();
            d.bprecio   = $('#buscar_tb_precio').val();

        }
    
    },

    "columns":[
        { "data": "codigoProducto"          },
        { "data": "marcaProducto"           },
        { "data": "tipoProducto"            },
        { "data": "disponiblesProducto"     },
        { "data": "nombreProducto"          },
        { "data": "precioProducto"          },
        { "data": "idProducto"              },
    ],

    "fnRowCallback": function(nRow, aData, iDisplayIndex) {

        let btnEstado ='';
        
        btnEstado += '<button type="button" class="btn btn-gradient-success btn-rounded btn-icon seleccionarProductoDetalle">';
        btnEstado += '<i class="mdi mdi-checkbox-marked-circle-outline"></i></button>';
        
        btnEstado += '</button>';
        $(nRow).find("td:eq(6)").html(btnEstado);
        
    }
});

$('#buscar_tb_codigo').on('keyup', function() {
    $("#tb_buscarProducto").DataTable().ajax.reload();
});

$('#buscar_tb_marca').on('keyup', function() {
    $("#tb_buscarProducto").DataTable().ajax.reload();
});

$('#buscar_tb_tipo').on('keyup', function() {
    $("#tb_buscarProducto").DataTable().ajax.reload();
});

$('#buscar_tb_nombre').on('keyup', function() {
    $("#tb_buscarProducto").DataTable().ajax.reload();
});

$('#buscar_tb_precio').on('keyup', function() {
    $("#tb_buscarProducto").DataTable().ajax.reload();
});


