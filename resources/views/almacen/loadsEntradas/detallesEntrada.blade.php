
<?php
$contador = 0;
?>
@foreach($entradasProductos as $entradasProducto)
<?php $contador = $contador+1; 
    $precio = explode("S/", $entradasProducto->precioProductoEntrada  );
    $importe = $precio[1]*$entradasProducto->cantidadProductoEntrada;
?>
<tr>
    <td>{{ $contador }}</td>
    <td>{{ $entradasProducto->nombreProducto }}</td>
    <td>{{ $entradasProducto->precioProductoEntrada }}</td>
    <td>{{ $entradasProducto->cantidadProductoEntrada }}</td>
    <td>S/{{ sprintf("%.2f", $importe)}}</td>

    
</tr>
@endforeach