$(document).ready(function() {
    var URLactual = window.location.href;
    var ultimateUrl = URLactual.substr(-1,1)
    var x  = URLactual.length
    if(ultimateUrl == '/' || ultimateUrl == '#' ){
        var URLactual = URLactual.substr(0,x-1);
    }
    
    var dt = $('#tb_entradas').DataTable({
                    "processing": false,
                    "serverSide": true,
                    "language": { 'url': "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json" },
                    "ajax": URLactual+"/tb_entradas", 
                    "columns":[
                        
                        { "data": "facturaEntrada" },
                        { "data": "rucProveedor" },
                        { "data": "nombreProveedor" },
                        { "data": "fechaEntrada" },
                        { "data": "idEntrada" },
                    ],


                    "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                        var btnVer = '<button class="btn btn-sm btn-gradient-primary ver" type="button"><i class="mdi mdi-eye"></i></button>';
                        var btnEdit = '<button class="btn btn-sm btn-gradient-secondary editar" type="button"><i class="mdi mdi-lead-pencil"></i></button>';
                        var btnDelete = '<button class="btn btn-sm btn-gradient-danger eliminar" type="button"><i class="mdi mdi-delete"></i></button>';
                        // $(nRow).find("td:eq(4)").html(btnVer+" "+btnEdit+" "+btnDelete);
                        $(nRow).find("td:eq(4)").html(btnVer);

                        
                        $(nRow).find("td:eq(0)").html('F'+aData['facturaEntrada']);
                        
                    }
                });

    $("#tb_entradas").on('click', '.ver', function(){
        var data = dt.row($(this).parents('tr')).data();
        var numeroEntrada = data['facturaEntrada'];
        var id = data['idEntrada'];
        let proveedor = data['nombreProveedor'];
        let fecha = data['fechaEntrada'];
        
        $('#proveedorEntradaDetalle').html(proveedor);
        $('#numeroEntradaDetalle').html(numeroEntrada);
        $('#fechaEntradaDetalle').html(fecha);

        var token = $("input[name='_token']").val();
        console.log(id);
        
        $.confirm({
            title: 'DETALLE DE LA ENTRADA F'+numeroEntrada+'!',
            theme: 'modern',
            animation: 'scale',
            type: 'green',
            content: function(){
                var self = this;
                self.setContent('');
                return $.ajax({
                    url: '/almacen/entrada/detalle/'+id,
                    method: 'get'
                }).done(function (response) {

                    $('#tablaDetallesEntradaModal tbody').html(response);
                    
                }).fail(function(){
                    // self.setContentAppend('<div>Fail!</div>');
                }).always(function(){
                    // self.setContentAppend('<div>Always!</div>');
                });
            },
            buttons: {
                Aceptar:{
                    text: 'Aceptar',
                    btnClass: 'btn-success',
                    action: function(){
                        $("#entradaDetalladaModal").modal('show')
                    }
                },
            },
            
            onContentReady: function(){
                // this.setContentAppend('<div>Content ready!</div>');
            }
        });
    }); 
})