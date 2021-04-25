@extends('layouts.blank')
@section('title')
    Descuentos
@endsection
@section('content')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Acciones</h4>
            <button type="button" 
                data-toggle="modal" 
                data-target="#crearDescuentoModal" 
                class="btn btn-gradient-primary btn-rounded btn-fw">Crear un nuevo descuento</button>
        </div>
    </div> 
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Descuentos</h4>
            <table id="tb_descuentos" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Codigo del descuento</th>
                        <th>Codigo del producto</th>
                        <th>Nombre del producto</th>
                        <th>Cantidad</th>
                        <th>Porcentaje</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
            </table>  
        </div>
    </div>
</div>

<div id="crearDescuentoModal" class="modal fade bd-crearDescuentoModal-modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> Agregar descuento </h4>
                    <div class="form-group row">
                          <div class="col-sm-6">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="membershipRadios" id="cerrar" value="1" checked="">
                                Cerrar automaticamente
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="membershipRadios" id="abrir" value="0">
                                Mantenerla abierta
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                        </div>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_descuentoCrear">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12">
                                        <label>BUSCA TU PRODUCTO POR EL <b>CODIGO</b></label>
                                        <select class="form-control" name="codigoProductoCrear" id="codigoProductoCrear" style="width: 90%;" >
                                            @foreach($productos as $producto)
                                                <option value="{{ $producto->id }}" > {{ $producto->codigo }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12">
                                        <label>BUSCA TU PRODUCTO POR SU <b>NOMBRE</b></label>
                                        <select class="form-control" name="nombreProductoCrear" id="nombreProductoCrear" style="width: 90%;">
                                            @foreach($productos as $producto)
                                                <option value="{{ $producto->id }}" > {{ $producto->nombre }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label> Porcentaje % </label>
                                        <input type="email" class="form-control" name="porcentajeCrear" id="porcentajeCrear">
                                    </div>
                                    <div class="col-6">
                                        <label>CANTIDAD</label>
                                        <input type="number" class="form-control" name="cantidadCrear" id="cantidadCrear">  
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn-gradient-primary mr-2" id="descuentoCrear">CREAR</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">CANCELAR</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="editarDescuentoModal" class="modal fade bd-editarDescuentoModal-modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> Editar descuento </h4>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_descuentoEditar">
                            @csrf
                            <input type="hidden" name="idEditar" id="idEditar">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12">
                                        <label>BUSCA TU PRODUCTO POR EL <b>CODIGO</b></label>
                                        <select class="form-control" name="codigoProductoEditar" id="codigoProductoEditar" style="width: 100%;" >
                                            @foreach($productos as $producto)
                                                <option value="{{ $producto->id }}" > {{ $producto->codigo }} - {{ $producto->nombre }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label> Porcentaje % </label>
                                        <input type="email" class="form-control" name="porcentajeEditar" id="porcentajeEditar">
                                    </div>
                                    <div class="col-6">
                                        <label>CANTIDAD</label>
                                        <input type="number" class="form-control" name="cantidadEditar" id="cantidadEditar">  
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn-gradient-primary mr-2" id="descuentoEditar">EDITAR</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">CANCELAR</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="pruebaDescuentoModal" class="modal fade bd-pruebaDescuentoModal-modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> Realizar pruebas de descuentos </h4>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label> PRODUCTO: </label><br>
                                    <span id="productoDescuentoPrueba"></span>
                                </div>
                                <div class="col-6">
                                    <label>PRECIO: </label><br>
                                    <span id="precioProductoPrueba"></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label> CANTIDAD: </label><br>
                                    <span id="cantidadDescuentoPrueba"></span>
                                </div>
                                <div class="col-6">
                                    <label>PORCENTAJE %:</label><br>
                                    <span id="porcentajeDescuentoPrueba"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-12">
                                    <label> CANTIDAD: </label>
                                    <input type="number" class="form-control" name="cantidadPrueba" id="cantidadPrueba">  
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label> IMPORTE SIN DESCUENTO: </label><br>
                                    <span id="importeSDPrueba">0.00</span>
                                </div>
                                <div class="col-6">
                                    <label>IMPORTE CON DESCUENTO:</label><br>
                                    <span id="importeCDPrueba">0.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label> TOTAL DE DESCUENTO: </label><br>
                                    <span id="totalDescuentoPrueba">0.00</span>
                                </div>
                                <div class="col-6">
                                    <label>VECES QUE SE APLICA EL DESCUENTO:</label><br>
                                    <span id="vecesDescuentoPrueba">0</span>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-gradient-primary mr-2" id="descuentoCrear">CREAR</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal">CANCELAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('script')
<!-- DATATABLES -->
<script type="text/javascript" src="{{ asset('js/Configuraciones/descuentos/datatables.js') }}"></script>

<!-- CREAR DESCUENTO -->
<script type="text/javascript" src="{{ asset('js/Configuraciones/descuentos/crearDescuento.js') }}"></script>

<!-- SELECT 2 -->
<script type="text/javascript" src="{{ asset('js/Configuraciones/descuentos/selects2.js') }}"></script>

<!-- PRUEBAS DE UN DESCUENTO -->
<script type="text/javascript" src="{{ asset('js/Configuraciones/descuentos/pruebasDescuento.js') }}"></script>

<script type="text/javascript">
    $('#codigoProductoCrear').change(function() {
        console.log($(this).val())
        console.log($("#nombreProductoCrear").val())
        $("#nombreProductoCrear").val($(this).val())
        $('#nombreProductoCrear').select2();
    });

    $('#nombreProductoCrear').change(function() {
        console.log($(this).val())
        console.log($("#codigoProductoCrear").val())
        $("#codigoProductoCrear").val($(this).val())
        $('#codigoProductoCrear').select2();
    });

</script>
@endsection
