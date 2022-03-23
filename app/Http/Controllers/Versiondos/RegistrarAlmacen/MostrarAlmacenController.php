<?php

namespace App\Http\Controllers\Versiondos\RegistrarAlmacen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\almacenes;

class MostrarAlmacenController extends Controller
{
    public function MostrarAlmacen(Request $request)
    {

        $idUsuario = $request->header('usuid');

        if($idUsuario == 1 || $idUsuario == 2){
            $idSucursal = 1;
        }else{
            $idSucursal = 2;
        }

        $almacenNegativo = array();
        $almacenPositivo = array();
        $almacenCero = array();

        $alms = almacenes::join('productos as p', 'p.id', 'almacenes.producto_id')
                            ->where('sucursal_id', $idSucursal)
                            ->where('almacenes.total', '!=', 0)
                            ->get([
                                'almacenes.id',
                                'p.codigo',
                                'p.nombre',
                                'p.precio',
                                'almacenes.stock',
                                'almacenes.vendido',
                                'almacenes.total',
                                'almacenes.transferenciarecibida',
                                'almacenes.transferenciarealizada',
                            ]);

        foreach($alms as $alm){

            if($alm->stock < 0){
                $almacenNegativo[] = array(
                    "id"      => $alm->id,
                    "codigo"  => $alm->codigo,
                    "nombre"  => $alm->nombre,
                    "precio"  => $alm->precio,
                    "stock"   => $alm->stock,
                    "vendido" => $alm->vendido,
                    "total"   => $alm->total,
                    "transferenciarecibida"  => $alm->transferenciarecibida,
                    "transferenciarealizada" => $alm->transferenciarealizada,
                );
            }

            if($alm->stock > 0){
                $almacenPositivo[] = array(
                    "id"      => $alm->id,
                    "codigo"  => $alm->codigo,
                    "nombre"  => $alm->nombre,
                    "precio"  => $alm->precio,
                    "stock"   => $alm->stock,
                    "vendido" => $alm->vendido,
                    "total"   => $alm->total,
                    "transferenciarecibida"  => $alm->transferenciarecibida,
                    "transferenciarealizada" => $alm->transferenciarealizada,
                );
            }

            if($alm->stock == 0){
                $almacenCero[] = array(
                    "id"      => $alm->id,
                    "codigo"  => $alm->codigo,
                    "nombre"  => $alm->nombre,
                    "precio"  => $alm->precio,
                    "stock"   => $alm->stock,
                    "vendido" => $alm->vendido,
                    "total"   => $alm->total,
                    "transferenciarecibida"  => $alm->transferenciarecibida,
                    "transferenciarealizada" => $alm->transferenciarealizada,
                );
            }

        }


        $rpta = array(
            'response'  => true,
            "todo"      => $alms,
            "positivos" => $almacenPositivo,
            "negativos" => $almacenNegativo,
            "ceros"     => $almacenCero
        );

        return json_encode($rpta);

    }

}
