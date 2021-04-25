$('#emitirBoleta').on('click', function(e) {
    let data = $('#frm_emitirBoleta').serialize();
    console.log(data);
    
    $.confirm({
        icon: 'fa fa-question',
        theme: 'modern',
        animation: 'scale',
        type: 'blue',
        title: '¿Está seguro de emitir esta boleta electrónica?',
        content: 'Si no desea emitirlo todavia lo puedo guardar con el boton de alado.',
        buttons: {
            Confirmar: function () {
                $.ajax({
                    url: '/venta/emitirBoleta',
                    type: 'post',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        if(response['response'] == true) {
                            toastr.success('Se emitio satisfactoriamente la boleta electrónica');
                            $("#btn_boleta").removeClass(" btn-gradient-danger");
                            $("#btn_boleta").removeClass("activado");
                            $("#btn_boleta").addClass("btn-gradient-primary");
                            $("#btn_boleta").addClass("desactivado");
                            $('#textBoleta').html('BOLETA DE VENTA ELECTRÓNICA');
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
