<!DOCTYPE html>
<html lang="en">
<head>
	<title> Endulzate | Login </title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<!-- <link rel="icon" type="image/png" href="{{ asset('assetsLogin/images/icons/favicon.ico')}}"/> -->
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assetsLogin/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assetsLogin/css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assetsLogin/css/main.css')}}">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
                <form class="login100-form validate-form" method="POST" action="{{ route('login') }}">
                @csrf
					<span class="login100-form-title p-b-34">
						Endulzate | Iniciar Sesión
					</span>
					
					<div class="wrap-input100 rs1-wrap-input100 validate-input m-b-20" data-validate="Type user name">
						<input id="first-name" class="input100" type="text" name="username" placeholder="User name">
						<span class="focus-input100"></span>
					</div>
					<div class="wrap-input100 rs2-wrap-input100 validate-input m-b-20" data-validate="Type password">
						<input class="input100" type="password" name="password" placeholder="Contraseña">
						<span class="focus-input100"></span>
					</div>
					
					<div class="container-login100-form-btn">
						<button type="submit" class="login100-form-btn">
							Iniciar Sesión
						</button>
                    </div>
					<div class="w-full text-center p-t-27 p-b-239">
						<a href="#" class="txt2">
							Usuario / contraseña?
						</a>
					</div>

				</form>

				<div class="login100-more" style="background-image: url('assetsLogin/images/bg-01.jpg');"></div>
			</div>
		</div>
	</div>
	
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="{{ asset('assetsLogin/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
	<script src="{{ asset('assetsLogin/js/main.js')}}"></script>

</body>
</html>