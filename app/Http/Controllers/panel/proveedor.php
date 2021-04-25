<?php

namespace App\Http\Controllers\panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\proveedores;
use Yajra\DataTables\DataTables;
use DB;
use App\control;

class proveedor extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        return view('almacen.proveedores');
    }

    public function tb_proveedores(Request $request)
    {
        return proveedores::where(function ($query) use($request) {
                                    if($request->get('buscar') != '') {
                                        $query->where('nombre', 'like', '%' . $request->get('buscar') . '%')
                                            ->orWhere('ruc', 'like', '%' . $request->get('buscar') . '%');
                                    }
                                })->paginate(10);
    }

    public function proveedorEditar(Request $request)
    {
        DB::beginTransaction();
        try {
            if($request['editarDireccionProveedor'] == null){
                $direccion = '';
            }else{
                $direccion = $request['editarDireccionProveedor'];
            }
            $proveedor = proveedores::find($request['editarIdProveedor']);
            $proveedor->nombre = $request['editarNombreProveedor'];
            $proveedor->ruc = $request['editarRucProveedor'];
            $proveedor->numero = $request['editarTelefonoProveedor'];
            $proveedor->direccion = $direccion;

            if($proveedor->update()) {
                $control = new control;
                $control->user_id = auth()->id();
                $control->metodo = "Editar";
                $control->tabla = "Proveedores";
                $control->campos = "nombre, ruc, numero, direccion";
                $control->datos = $request['editarIdProveedor'].', '. $request['editarNombreProveedor'].', '. $request['editarRucProveedor'].', '. $request['editarTelefonoProveedor'].', '. $request['editarDireccionProveedor'];
                $control->descripcion = "Editar un proveedor";
                $control->save();
            }

            DB::commit();

            $rpta = array(
                'response'          =>  true,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }


}
