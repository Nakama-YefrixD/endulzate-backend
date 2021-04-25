<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="utf-8">
	<title>Endulzate PDF</title>
	<link rel="stylesheet" href="{{public_path('pdf/style.css')}}">
  </head>
  <body>
	<header class="clearfix">
	  <div id="logo">
		<img src="{{public_path('img/logo.png')}}">
	  </div>
	  <h1>ENTRADA {{ $entrada->factura }}</h1>
	  <div id="company" class="clearfix">
		<div>ENDULZATE</div>
	  </div>
	  <div id="project">
		<div><span>FACTURA</span> {{ $entrada->factura }} </div>
		<div><span>FECHA</span> {{ $entrada->fecha }} </div>
		<div><span>PROVEEDOR</span> {{ $proveedor->nombre }} </div>
		<div><span>SUCURSAL</span> {{ $sucursal->nombre }} </div>
	  </div>
	</header>
	<main>
		<table>
			<thead>
				<tr>
					<th class="no">#</th>
					<th class="desc">PRODUCTO</th>
					<th class="unit">CANTIDAD</th>
					<th class="unit">PRECIO UNIT.</th>
					<th class="unit">IMPORTE</th>				
		  		</tr>
			</thead>
			<tbody>
			@foreach($productos as $producto)
          		<tr>
            		<td class="no"><span>{{ $item }}</span></td>
            		<td class="desc"><span>{{ $producto['nombre'] }}</span></td>
            		<td class="unit">{{ $producto['cantidad'] }}</td>
            		<td class="unit">S/{{ $producto['precio'] }}</td>
            		<td class="unit">S/{{ $producto['importe'] }}</td>
          		</tr>
          		@php
                	$item++
            	@endphp
        	@endforeach
        		<tr>
          			<td colspan="4" class="grand total">CANTIDAD TOTAL</td>
          			<td class="grand total"> {{ $cantidadT }} </td>
        		</tr>
        		<tr>
            		<td colspan="4" class="grand total">IMPORTE TOTAL</td>
            		<td class="grand total">S/ {{ $importeT }} </td>
          		</tr>
			</tbody>
		</table>
	</main>
	<footer>
      Informe detallado de la entrada para ENDULZATE
    </footer>
  </body>
</html>