@extends('layouts.blank')
@section('title')
    Ventas
@endsection
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Generar tipo de venta</h6>
            <button type="button" id= "btn_factura" 
                class="btn btn-gradient-primary btn-rounded btn-fw desactivado"><span id="textFactura">FACTURA ELECTRÓNICA<span></button>
            <button type="button" id= "btn_boleta" 
                class="btn btn-gradient-success btn-rounded btn-fw desactivado"><span id="textBoleta">BOLETA DE VENTA ELECTRÓNICA<span></button>
        </div>
    </div> 
</div>




<div class="col-lg-12 grid-margin stretch-card" id="contenedorEmitir" style="display: none">
    <div class="card">
        <div class="card-body">
            <div id="formularioElectronico">

            </div>
        </div>
    </div>
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Buscar</h4>
            @csrf
            <div class="row">
                <div class="col-3">
                    <label>Cliente</label>
                    <input type="text" class="form-control form-control-lg" name="buscar_tb_cliente" id="buscar_tb_cliente">
                </div>
                <div class="col-3">
                    <label>Tipo de comprobante</label>
                    <select class="form-control" name ="buscar_tb_comprobante" id="buscar_tb_comprobante">
                        <option value="">SELECCIONA UN COMPROBANTE</option>
                        <option value="BOLETA">BOLETA</option>
                        <option value="FACTURA">FACTURA</option>
                    </select>
                    <!-- <input type="text" class="form-control form-control-lg" name="buscar_tb_comprobante" id="buscar_tb_comprobante"> -->
                </div>
                <div class="col-3">
                    <label>Numero de comprobante</label>
                    <input type="text" class="form-control form-control-lg" name="buscar_tb_numeroComprobante" id="buscar_tb_numeroComprobante">
                </div>
                <div class="col-3">
                    <label>Filtro por Fechas</label>
                    <input type="text" class="form-control form-control-lg" name="buscar_tb_fecnumeroComprobante" id="buscar_tb_fecnumeroComprobante" value=''>
                </div>
                    
            </div>
        </div>
    </div> 
</div>


<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Comprobantes</h6>
            <table id="tb_ventas" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha Emisión</th>
                        <th>Cliente</th>
                        <th>Tipo Comprobante</th>
                        <th>Número</th>
                        <th>Estado</th>
                        <th>SubTotal</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
            </table>  
        </div>
    </div>
</div>


@endsection

@section('script')

<script type="text/javascript" src="{{ asset('js/ventas/comprobantes/tabla.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ventas/mostrarPdf.js') }}"></script>

<script type="text/javascript">
    
    $(document).ready(function() {
        $('input[name="buscar_tb_fecnumeroComprobante"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'YYYY/MM/DD',
                "daysOfWeek": [
                    "Do",
                    "Lu",
                    "Ma",
                    "Mi",
                    "Ju",
                    "Vi",
                    "Sa"
                ],
                "monthNames": [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ],
            }
        });
        $('input[name="buscar_tb_fecnumeroComprobante"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
            $("#tb_ventas").DataTable().ajax.reload();
        });

        $('input[name="buscar_tb_fecnumeroComprobante"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            $("#tb_ventas").DataTable().ajax.reload();
        });



        $('#btn_factura').on('click', function() {
            if ($('#btn_boleta').hasClass('activado')){
                $("#btn_boleta").removeClass(" btn-gradient-danger");
                $("#btn_boleta").removeClass("activado");
                $("#btn_boleta").addClass("btn-gradient-primary");
                $("#btn_boleta").addClass("desactivado");
                $('#textBoleta').html('BOLETA DE VENTA ELECTRÓNICA');
                $('#formularioElectronico').html('');
            }
            if ($(this).hasClass('desactivado')){
                $.confirm({
                    title: 'FACTURA ELECTRÓNICA',
                    theme: 'modern',
                    animation: 'scale',
                    type: 'purple',
                    content: function(){
                        var self = this;
                        self.setContent('Estamos listos para empezar a facturar!');
                        return $.ajax({
                            url: '/ventas/loads/frmfactura',
                            method: 'get'
                        }).done(function (response) {
                            // self.setContentAppend(response);
                            $("#btn_factura").removeClass("btn-gradient-primary");
                            $("#btn_factura").removeClass("desactivado");
                            $("#btn_factura").addClass("btn-gradient-danger");
                            $("#btn_factura").addClass("activado");
                            $('#textFactura').html('CANCELAR FACTURA ELECTRÓNICA');
                            $('#contenedorEmitir').show();
                            $('#formularioElectronico').html(response);
                        }).fail(function(){
                            // self.setContentAppend('<div>Fail!</div>');
                        }).always(function(){
                            // self.setContentAppend('<div>Always!</div>');
                        });
                    },
                    buttons: {
                        Aceptar:{
                            text: 'Aceptar',
                            btnClass: 'btn-primary',
                            action: function(){
                            }
                        },
                    },
                    
                    onContentReady: function(){
                        // this.setContentAppend('<div>Content ready!</div>');
                    }
                });
            }else{
                location.reload();
                $("#btn_factura").removeClass(" btn-gradient-danger");
                $("#btn_factura").removeClass("activado");
                $("#btn_factura").addClass("btn-gradient-primary");
                $("#btn_factura").addClass("desactivado");
                $('#textFactura').html('FACTURA ELECTRÓNICA');
                $('#contenedorEmitir').hide();
                $('#formularioElectronico').html('');
            }
        });

        $('#btn_boleta').on('click', function() {
            if ($('#btn_factura').hasClass('activado')){
                $("#btn_factura").removeClass(" btn-gradient-danger");
                $("#btn_factura").removeClass("activado");
                $("#btn_factura").addClass("btn-gradient-primary");
                $("#btn_factura").addClass("desactivado");
                $('#textFactura').html('FACTURA ELECTRÓNICA');
                $('#formularioElectronico').html('');
            }
            if ($(this).hasClass('desactivado')){
                $.confirm({
                    title: 'BOLETA ELECTRÓNICA',
                    theme: 'modern',
                    animation: 'scale',
                    type: 'green',
                    content: function(){
                        var self = this;
                        self.setContent('Estamos listos para empezar con la boleta!');
                        return $.ajax({
                            url: '/ventas/loads/frmboleta',
                            method: 'get'
                        }).done(function (response) {
                            // self.setContentAppend(response);
                            $("#btn_boleta").removeClass("btn-gradient-primary");
                            $("#btn_boleta").removeClass("desactivado");
                            $("#btn_boleta").addClass("btn-gradient-danger");
                            $("#btn_boleta").addClass("activado");
                            $('#textBoleta').html('CANCELAR BOLETA DE VENTA ELECTRÓNICA');
                            $('#contenedorEmitir').show();
                            $('#formularioElectronico').html(response);
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
                            }
                        },
                    },
                    
                    onContentReady: function(){
                        // this.setContentAppend('<div>Content ready!</div>');
                    }
                });
            }else{
                $("#btn_boleta").removeClass(" btn-gradient-danger");
                $("#btn_boleta").removeClass("activado");
                $("#btn_boleta").addClass("btn-gradient-primary");
                $("#btn_boleta").addClass("desactivado");
                $('#textBoleta').html('BOLETA DE VENTA ELECTRÓNICA');
                $('#contenedorEmitir').hide();
                $('#formularioElectronico').html('');
            }
        });
    })
</script>


@endsection
