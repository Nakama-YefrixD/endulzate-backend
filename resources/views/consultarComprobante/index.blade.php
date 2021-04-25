<!DOCTYPE html>
<html lang="en">
<head>
	<title>CONSULTAR | PRECIOSA</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="{{ asset('assetsConsultar/images/icons/favicon.ico')}}"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assetsConsultar/vendor/bootstrap/css/bootstrap.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assetsConsultar/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assetsConsultar/fonts/iconic/css/material-design-iconic-font.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assetsConsultar/vendor/animate/animate.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assetsConsultar/vendor/css-hamburgers/hamburgers.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assetsConsultar/vendor/animsition/css/animsition.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assetsConsultar/vendor/select2/select2.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assetsConsultar/vendor/daterangepicker/daterangepicker.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assetsConsultar/vendor/noui/nouislider.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assetsConsultar/css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assetsConsultar/css/main.css')}}">
<!--===============================================================================================-->

    {!! htmlScriptTagJsApi() !!}
</head>
<body>


	<div class="container-contact100">
		<div class="wrap-contact100">
			<form class="contact100-form validate-form" method="post" role="form" id="frm_consulta">
			@csrf
				<span class="contact100-form-title">
                    CONSULTA DE<br>
                    Comprabantes electronicos
				</span>

                <div class="wrap-input100 input100-select bg1">
					<span class="label-input100">Tipo Documento *</span>
					<div>
						<select class="js-select2" name="tipodocumento">
                            @foreach($tiposcomprobantes as $tipocomprobante)
                            <option value="{{ $tipocomprobante->id }}">{{ $tipocomprobante->nombre }}</option>
							@endforeach
						</select>
						<div class="dropDownSelect2"></div>
					</div>
                </div>

				<div class="wrap-input100 validate-input bg1 rs1-wrap-input100" data-validate = "No es una serie valida">
					<span class="label-input100">Serie *</span>
					<input class="input100" type="text" name="serie" placeholder="F001 ">
				</div>
                <br>-
				<div class="wrap-input100 validate-input bg1 rs1-wrap-input100" data-validate = "No es un correlativo valido">
					<span class="label-input100">Número *</span>
					<input class="input100" type="text" name="numero" placeholder="000001">
				</div>

                <div class="wrap-input100 validate-input bg1" data-validate="">
                    <span class="label-input100">Fecha Emisión *</span>
                    <input class="input100" type="text" name="fechaEmision" value="{{ $fechaActual }}"  />
                </div>
				<div class="wrap-input100 validate-input bg1" data-validate="">
					{!! htmlFormSnippet() !!}
					@if($captcha == 1)
						<strong>Hey!!! Debes demostrar que no eres un robot ! </strong>
					@endif
						<strong id="errorCaptcha" style="display:none;">Hey!!! Debes demostrar que no eres un robot ! </strong>
                </div>
				<div class="container-contact100-form-btn">
					<button type="button" class="contact100-form-btn" id="btn_consultar">
						<span>
							Consultar 
							<i class="fa fa-long-arrow-right m-l-7" aria-hidden="true"></i>
						</span>
					</button>
				</div>
				<div class="container-contact100-form-btn" id="opcionesConsulta" style="display:none;">
					<button type="button" class="contact50-form-btn" id="btn_verPdf" >
						<span>
							Ver pdf 
						</span>
					</button>
				
					<button type="button" class="contact50-form-btn" id="btn_descargarXml" style="padding-left:5em">
						<span>
							Descargar XML 
						</span>
					</button>
				</div>

			</form>
		</div>
	</div>



<!--===============================================================================================-->
	<script src="{{ asset('assetsConsultar/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('assetsConsultar/vendor/animsition/js/animsition.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('assetsConsultar/vendor/bootstrap/js/popper.js')}}"></script>
	<script src="{{ asset('assetsConsultar/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('assetsConsultar/vendor/select2/select2.min.js')}}"></script>
	<script>
		$(".js-select2").each(function(){
			$(this).select2({
				minimumResultsForSearch: 20,
				dropdownParent: $(this).next('.dropDownSelect2')
			});
		})

		let idVenta = 0;
		$('#btn_consultar').on('click', function(e) {
			let data = $('#frm_consulta').serialize();
			console.log(data);
			$.ajax({
				url: '/consultar/captcha',
				type: 'post',
				data: data ,
				dataType: 'json',
				success: function(response) {
					if(response['response'] == true) {
						
						$('#errorCaptcha').hide();
						if(response['venta'] == true){
							idVenta = response['idVenta'];
							$('#opcionesConsulta').show();
							
						}else{
							alert('No existe la venta');
							location.reload();
						}


					}else{
						$('#errorCaptcha').show();
					}
				},
				error: function(response) {
					
				}
			});
		});
		
		$('#btn_verPdf').on('click', function(e) {
			window.open("/consultar/pdf/"+idVenta);
		});

		$('#btn_descargarXml').on('click', function(e) {
			window.open("/consultar/xml/"+idVenta);
		});

		

	</script>
<!--===============================================================================================-->
	<script src="{{ asset('assetsConsultar/vendor/daterangepicker/moment.min.js')}}"></script>
	<script src="{{ asset('assetsConsultar/vendor/daterangepicker/daterangepicker.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('assetsConsultar/vendor/countdowntime/countdowntime.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('assetsConsultar/vendor/noui/nouislider.min.js')}}"></script>
	<script>
	    var filterBar = document.getElementById('filter-bar');

	    noUiSlider.create(filterBar, {
	        start: [ 1500, 3900 ],
	        connect: true,
	        range: {
	            'min': 1500,
	            'max': 7500
	        }
	    });

	    var skipValues = [
	    document.getElementById('value-lower'),
	    document.getElementById('value-upper')
	    ];

	    filterBar.noUiSlider.on('update', function( values, handle ) {
	        skipValues[handle].innerHTML = Math.round(values[handle]);
	        $('.contact100-form-range-value input[name="from-value"]').val($('#value-lower').html());
	        $('.contact100-form-range-value input[name="to-value"]').val($('#value-upper').html());
	    });
	</script>
<!--===============================================================================================-->
	<script src="{{ asset('assetsConsultar/js/main.js')}}"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-23581568-13');
</script>
<script>
    $(function() {
        $('input[name="fechaEmision"]').daterangepicker({
            locale: { 
                format: 'DD-MM-YYYY'
            },
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            maxYear: parseInt(moment().format('YY'),10)
        }, function(start, end, label) {
            var years = moment().diff(start, 'years');
        });
    });
</script>
</body>
</html>
