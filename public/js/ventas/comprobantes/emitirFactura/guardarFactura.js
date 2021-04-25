$('#guardarFactura').on('click', function(e) {
    let data = $('#frm_editar_producto').serialize();
    console.log(data);
    
    $.confirm({
        icon: 'fa fa-question',
        theme: 'modern',
        animation: 'scale',
        type: 'green',
        title: '¿Está seguro de guardar este documento electrónico?',
        content: 'Si no desea guardarlo lo puede emitir con el boton de alado.',
        buttons: {
            Confirmar: function () {
                $.ajax({
                    url: '/venta/guardarEmitirfactura',
                    type: 'post',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        if(response['response'] == true) {
                            toastr.success('Se guardo satisfactoriamente el documento electrónico');
                            $("#btn_factura").removeClass(" btn-gradient-danger");
                            $("#btn_factura").removeClass("activado");
                            $("#btn_factura").addClass("btn-gradient-primary");
                            $("#btn_factura").addClass("desactivado");
                            $('#textFactura').html('FACTURA ELECTRÓNICA');
                            $('#formularioElectronico').html('');

                            
                        } else if(response['response'] == false){
                            toastr.error('Este numero de factura ya existe.');
                        }else {
                            // toastr.error(response.responseText);
                            toastr.error('Ocurrio un error al momento de guardar este tipo de documento electrónico porfavor verifique si todos los campos estan correctos');
                        }
                    },
                    error: function(response) {
                        // toastr.error(response.responseText);
                        toastr.error('Ocurrio un error al momento de guardar este tipo de documento electrónico porfavor verifique si todos los campos estan correctos');
                        
                    }
                });
            },
            Cancelar: function () {
                
            }
        }
    });
});