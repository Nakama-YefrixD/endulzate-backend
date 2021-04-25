<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tipos;
use Yajra\DataTables\DataTables;
use App\control;
use DB;

class tiposController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public static function datos()
    {
        $tipos = Tipos::all();
        return $tipos;        
    }

    public function index()
    {
        // return view('almacen.tiposProductos');
        return view('react.app');
    }

    public function tb_tiposProductos(Request $request)
    {
        return Tipos::where(function ($query) use($request) {
                                    if($request->get('bnombre') != '') {
                                        $query->where('nombre', 'like', '%' . $request->get('bnombre') . '%');
                                    }
                                })->paginate(1000);
    }

    public function tiposProductosEditar(Request $request)
    {
        DB::beginTransaction();
        try {
            
            $tiposProductos = Tipos::find($request['editarIdTipoProducto']);
            $tiposProductos->nombre = $request['editarNombreTipoProducto'];
            
            if($tiposProductos->update()) {
                $control = new control;
                $control->user_id = auth()->id();
                $control->metodo = "Editar";
                $control->tabla = "Tipos";
                $control->campos = "all";
                $control->datos = $request['editarIdTipoProducto'].', '. $request['editarNombreTipoProducto'];
                $control->descripcion = "Editar un tipo de producto";
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
    
    public function tiposProductosEliminar(Request $request)
    {
        DB::beginTransaction();
        try {
            
            $tiposProductos = Tipos::find($request['id']);
            if($tiposProductos->delete()){
                $control = new control;
                $control->user_id = auth()->id();
                $control->metodo = "Eliminar";
                $control->tabla = "Tipos";
                $control->campos = "all";
                $control->datos = $request['id'];
                $control->descripcion = "Eliminar todos los tipos de productos";
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
