$('#emitirFactura').on('click', function(e) {
    let data = $('#frm_editar_producto').serialize();
    console.log(data);
    
    $.confirm({
        icon: 'fa fa-question',
        theme: 'modern',
        animation: 'scale',
        type: 'blue',
        title: '¿Está seguro de emitir este documento electrónico?',
        content: 'Si no desea emitirlo todavia lo puedo guardar con el boton de alado.',
        buttons: {
            Confirmar: function () {
                $.ajax({
                    url: '/venta/emitirfactura',
                    type: 'post',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        if(response['response'] == true) {
                            toastr.success('Se emitio satisfactoriamente el documento electrónico');
                            $("#btn_factura").removeClass(" btn-gradient-danger");
                            $("#btn_factura").removeClass("activado");
                            $("#btn_factura").addClass("btn-gradient-primary");
                            $("#btn_factura").addClass("desactivado");
                            $('#textFactura').html('FACTURA ELECTRÓNICA');
                            $('#formularioElectronico').html('');

                            
                        } else {
                            // toastr.error(response.responseText);
                            toastr.error('Ocurrio un error al momento de emitir este tipo de documento electrónico porfavor verifique si todos los campos estan correctos');
                        }
                    },
                    error: function(response) {
                        // toastr.error(response.responseText);
                        toastr.error('Ocurrio un error al momento de emitir este tipo de documento electrónico porfavor verifique si todos los campos estan correctos');
                        
                    }
                });
            },
            Cancelar: function () {
                
            }
        }
    });
});