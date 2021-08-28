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
      <h1>VENTA {{ $venta->numero }}</h1>
      <div id="company" class="clearfix">
        <div>ENDULZATE</div>
        <div> - <br /> - </div>
        <div> - </div>
        <div><a href="">ENDULZATE@hotmail.com</a></div>
      </div>
      <div id="project">
        <div><span>CLIENTE</span> {{ $cliente->nombre }} </div>
        <div><span>DIRECCIÓN</span> {{ $cliente->direccion }} </div>
        <div><span>EMAIL</span> <a href="mailto:john@example.com">{{ $cliente->email }}</a></div>
      </div>
    </header>
    <main>
      <table>
        <thead>
          <tr>
            <th class="no">#</th>
            <th class="desc">PRODUCTO</th>
            <th class="unit">PRECIO</th>
            <th class="qty">DESCUENTO</th>
            <th class="unit">CANTIDAD</th>
            <th class="total">TOTAL</th>

            <!-- <th class="service">SERVICE</th>
            <th class="desc">DESCRIPTION</th>
            <th>PRICE</th>
            <th>QTY</th>
            <th>TOTAL</th> -->
          </tr>
        </thead>
        <tbody>
        @foreach($detallesVenta as $detalleVenta)
          <tr>
            <td class="no"><span>{{ $item }}</span></td>
            <td class="desc"><span>{{ $detalleVenta->nombreProducto }}</span></td>
            <td class="unit">S/{{ $detalleVenta->precioProducto }}</td>
            <td class="qty">{{ $detalleVenta->descuentoProducto}}</td>
            <td class="unit">{{ $detalleVenta->cantidadProducto}}</td>
            <td class="total">S/{{ $detalleVenta->totalProducto }}</td>
          </tr>
          @php
                $item++
            @endphp
        @endforeach
        <tr>
          <td colspan="5">DESCUENTO</td>
          <td class="total">S/ {{ $venta->descuento }}</td>
        </tr>
        <tr>
          <td colspan="5">SUBTOTAL</td>
          <td class="total">S/ {{ $venta->subtotal }}</td>
        </tr>
        <tr>
          <td colspan="5">IGV 18%</td>
          <td class="total">S/ {{ $venta->impuestos }} </td>
        </tr>
        <tr>
            <td colspan="5" class="grand total">TOTAL</td>
            <td class="grand total">S/ {{ $venta->total }} </td>
          </tr>
        </tbody>
      </table>
      <div id="notices">
        <div>Observacion:</div>
        <div class="notice">{{ $venta->Observaciones }}</div>
      </div>
    </main>
    <footer>
      Informe detallado de la venta para ENDULZATE
    </footer>
  </body>
</html>