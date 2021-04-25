<?php 
  use\App\Http\Controllers\ProductosController; 
  $productos = ProductosController::index(); 
?>

@foreach($productos as $producto)
    <option value="{{ $producto->id }}" >{{ $producto->nombre }}</option>
@endforeach