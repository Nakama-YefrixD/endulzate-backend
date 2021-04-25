$('#descuentoCrear').on('click', function(e) {
    let data = $('#frm_descuentoCrear').serialize();
    console.log(data);
    $.confirm({
        icon: 'fa fa-question',
        theme: 'modern',
        animation: 'scale',
        type: 'blue',
        title: '¿Está seguro de crear este descuento?',
        content: 'Este descuento se aplicara al momento de realizar un comprabante de pago.',
        buttons: {
            Confirmar: function () {
                $.ajax({
                    url: '/configuraciones/descuentos/crear',
                    type: 'post',
                    data: data ,
                    dataType: 'json',
                    success: function(response) {
                        if(response['response'] == true) {
                            toastr.success('Se grabó satisfactoriamente el descuento');
                            $("#tb_descuentos").DataTable().ajax.reload();
                            $('#cantidadCrear').val('');
                            $('#porcentajeCrear').val('');
                            if($('#cerrar').val() == 1){
                                $('#crearDescuentoModal').modal('hide');
                            }
                        } else {
                            toastr.error('Ocurrio un error al momento de crear este descuento porfavor verifique si todos los campos estan correctos');
                        }
                    },
                    error: function(response) {
                        toastr.error('Ocurrio un error al momento de crear este descuento porfavor verifique si todos los campos estan correctos');
                    }
                });
            },
            Cancelar: function () {
                
            }
        }
    });
});