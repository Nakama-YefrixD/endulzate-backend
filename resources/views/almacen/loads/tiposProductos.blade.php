<?php 
  use\App\Http\Controllers\tiposController; 
  $tipos = tiposController::tiposGet(); 
?>

@foreach($tipos as $tipo)
    <option value="{{ $tipo->id }}" > {{ $tipo->nombre }} </option>
@endforeach