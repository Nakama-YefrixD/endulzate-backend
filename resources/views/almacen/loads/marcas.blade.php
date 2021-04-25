<?php 
  use\App\Http\Controllers\MarcasController; 
  $marcas = MarcasController::index(); 
?>

@foreach($marcas as $marca)
    <option value="{{ $marca->id }}" > {{ $marca->nombre }} </option>
@endforeach