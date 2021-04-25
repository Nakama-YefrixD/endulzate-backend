$('#guardarBoleta').on('click', function(e) {
    let data = $('#frm_emitirBoleta').serialize();
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
                    url: '/venta/guardaremitirBoleta',
                    type: 'post',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        if(response['response'] == true) {
                            toastr.success('Se guardo satisfactoriamente el documento electrónico');
                            $("#btn_boleta").removeClass(" btn-gradient-danger");
                            $("#btn_boleta").removeClass("activado");
                            $("#btn_boleta").addClass("btn-gradient-primary");
                            $("#btn_boleta").addClass("desactivado");
                            $('#textBoleta').html('BOLETA DE VENTA ELECTRÓNICA');
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