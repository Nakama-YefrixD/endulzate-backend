<?php

namespace App\Http\Controllers;

use App\marcas;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\control;
use DB;

class MarcasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function datos()
    {
        $marcas = marcas::all();
        return $marcas;      
    }

    public function show()
    {
        $marcas = marcas::all();
        
        // return view('almacen.marcas');
        return view('react.app');
    }

    public function tb_marcas(Request $request)
    {
        return marcas::where(function ($query) use($request) {
                                    if($request->get('bnombre') != ''){
                                        $query->where('marcas.nombre', 'like', '%' . $request->get('bnombre') . '%');
                                    }
                                })->paginate(1000);
    }

    public function marcasEditar(Request $request)
    {
        DB::beginTransaction();
        try {
            
            $marcas = marcas::find($request['editarIdMarca']);
            $marcas->nombre = $request['editarNombreMarca'];
            
            if($marcas->update()) {
                $control = new control;
                $control->user_id = auth()->id();
                $control->metodo = "Editar";
                $control->tabla = "Marcas";
                $control->campos = "all";
                $control->datos = $request['editarIdMarca'].', '. $request['editarNombreMarca'];
                $control->descripcion = "Editar una marca";
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

    public function marcasEliminar(Request $request)
    {
        DB::beginTransaction();
        try {
            
            $marcas = marcas::find($request['id']);
            if($marcas->delete()){
                $control = new control;
                $control->user_id = auth()->id();
                $control->metodo = "Eliminar";
                $control->tabla = "Marcas";
                $control->campos = "all";
                $control->datos = $request['id'];
                $control->descripcion = "Eliminar una marca especifica";
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

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\marcas  $marcas
     * @return \Illuminate\Http\Response
     */
    public function edit(marcas $marcas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\marcas  $marcas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, marcas $marcas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\marcas  $marcas
     * @return \Illuminate\Http\Response
     */
    public function destroy(marcas $marcas)
    {
        //
    }
}
