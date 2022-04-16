<?php

namespace App\Http\Controllers\Versiondos\Ventas\ReporteVentas\MostrarDetalleReportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ventas;

class MostrarVentasController extends Controller
{
    public function MostrarDetallesVentas(Request $request)
    {
        
        $idUsuario = $request->header('usuid');
        $re_fechainicio = $request->get('re_fechainicio');
        $re_fechafinal  = $request->get('re_fechafinal');

    	if ($idUsuario == 1 || $idUsuario == 2) {
    		$idSucursal = 1;
    	} else {
    		$idSucursal = 2;
    	}

        return ventas::join('tiposComprobantes', 'ventas.tipoComprobante_id', '=', 'tiposComprobantes.id')
                        ->join('clientes', 'ventas.cliente_id', '=', 'clientes.id')
                        ->where('ventas.sucursal_id', $idSucursal)
                        ->whereBetween('ventas.created_at', [$re_fechainicio, $re_fechafinal])
                        ->where('ventas.estadoSunat', 0)
                        ->orderBy('ventas.numero', 'desc')
                        ->select(
                            'ventas.id                  as idVentas',
                            'tiposComprobantes.id       as idTiposcomprobante',
                            'tiposComprobantes.nombre   as nombreTiposcomprobante',
                            'ventas.fecha               as fechaVentas',
                            'clientes.nombre            as nombreClientes',
                            'ventas.numero              as numeroVentas',
                            'ventas.estadoSunat         as estadoSunatVentas',
                            'ventas.subtotal            as subTotalVentas',
                            'ventas.total               as totalVentas',
                            'ventas.tipoMoneda_id'
                            
                        )->paginate(10);

    }
}
