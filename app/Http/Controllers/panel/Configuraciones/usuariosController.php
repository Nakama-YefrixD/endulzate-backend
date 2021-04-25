<?php

namespace App\Http\Controllers\panel\Configuraciones;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use App\User;
use App\Control;
use App\sucursales;
use App\usuariosSucursales;
use DB;

class usuariosController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
          return view('react.app');
    }


    public function tb_usuarios(Request $request)
    {
        $usuarios = User::where(function ($query) use($request) {
                            if ($request->buscar != '') {
                                $query->where('users.username', 'like', '%' . $request->buscar . '%')
                                    ->orWhere('users.name', 'like', '%' . $request->buscar . '%');
                                }
                            })
                            ->select('id')
                            ->paginate(10);

        $answer = [];

        foreach ($usuarios as $usuario) {
            $parte_A = User::find($usuario->id, ['id', 'username', 'name']);
            $parte_B = usuariosSucursales::where([
                                            ['user_id', $parte_A->id],
                                            ['predeterminado', 1]
                                        ])
                                        ->first('sucursal_id');
            $parte_C = User::find($usuario->id)->sucursales()
                                ->get(['codigo', 'nombre']);

            $parte_A['predeterminado'] = $parte_B->sucursal_id;
            $parte_A['sucursales'] = $parte_C;
            $answer[] = $parte_A;
        }

        return $answer;
    }

    public function tb_sucursales()
    {
        return sucursales::get([
            'id',
            'codigo',
            'nombre'
        ]);
    }

    public function usuarioEditar(Request $request)
    {
        DB::beginTransaction();

        try {

            $usuario = User::find($request['id']);
            $usuario->name = $request['nombre'];
            $usuario->username = $request['username'];

            if ($request->password) {
                $usuario->password = Hash::make($request->password);
            }

            //MOTORCITO PARA EDITAR SUCURSALES :3
            if (count($request->sucursales) == 1) {

                $asignados = usuariosSucursales::where('user_id', $request->id)->get();

                if (count($asignados) > 1) {
                    foreach ($asignados as $asignado) {

                        $existe = usuariosSucursales::where([
                            ['sucursal_id', $request->sucursales[0]],
                            ['user_id', $request->id]
                        ])->first();

                        if ($asignado->sucursal_id != $request->sucursales[0]) {
                            usuariosSucursales::where([
                                ['sucursal_id', $asignado->sucursal_id],
                                ['user_id', $request->id]
                            ])->delete();
                        }
                        if (!$existe) {
                            $crear = new usuariosSucursales;
                            $crear->sucursal_id = $request->sucursales[0];
                            $crear->user_id =  $request->id;
                            $crear->predeterminado = 1;
                            $crear->save();
                        } else {
                            $editar = usuariosSucursales::find($existe->id);
                            $editar->predeterminado = 1;
                            $editar->save();  
                        }
                    }
                } else {
                    $only_one = $asignados[0];
                    $only_one->sucursal_id = $request->sucursales[0];
                    $only_one->predeterminado = 1;
                    $only_one->save();
                }

            } else if (count($request->sucursales) > 1) {

                $asignados = usuariosSucursales::where('user_id', $request->id)->get();

                foreach ($asignados as $asignado) {
                    foreach ($request->sucursales as $sucursal) {

                        $existe = usuariosSucursales::where([
                            ['sucursal_id', $sucursal],
                            ['user_id', $request->id]
                        ])->first();

                        if (in_array($asignado->sucursal_id, $request->sucursales) == false) {
                            usuariosSucursales::where([
                                ['sucursal_id', $asignado->sucursal_id],
                                ['user_id', $request->id]
                            ])->delete();
                        }
                        if (!$existe) {
                            $crear = new usuariosSucursales;
                            $crear->sucursal_id = $sucursal;
                            $crear->user_id =  $request->id;

                            if ($request->predeterminado == $sucursal) {
                                $crear->predeterminado = 1;
                            } else {
                                $crear->predeterminado = 0;
                            }

                            $crear->save();
                        } else {
                            $editar = usuariosSucursales::find($existe->id);

                            if ($request->predeterminado == $sucursal) {
                                $editar->predeterminado = 1;
                            } else {
                                $editar->predeterminado = 0;
                            }

                            $editar->save();
                        }
                    }
                }
            }
            //FIN DEL MOTORCITO

            $usuario->save();

            DB::commit();

            $rpta = array(
                'response' => true,
            );
            echo json_encode($rpta);

        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }

    public function usuarioCrear(Request $request)
    {
        DB::beginTransaction();

        try {

            $usuario = new User;
            $usuario->name = $request['nombre'];
            $usuario->username = $request['username'];
            $usuario->password = Hash::make($request['password']);
            $usuario->imagen = 'usuarioHombre.png';
            $usuario->save();

            $usuarioId = User::where('username', $request->username)->first('id');

            foreach ($request->sucursales as $sucursal) {
                $asignar = new usuariosSucursales;
                $asignar->sucursal_id = $sucursal;
                $asignar->user_id =  $usuarioId->id;

                if ($request->predeterminado == $sucursal) {
                    $asignar->predeterminado = 1;
                } else {
                    $asignar->predeterminado = 0;
                }

                $asignar->save();
             }

            DB::commit();

            $rpta = array(
                'response' => true,
            );
            echo json_encode($rpta);

        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }
}
