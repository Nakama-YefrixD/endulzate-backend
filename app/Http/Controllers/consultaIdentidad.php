<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\clientes;
use App\ventas;

use Peru\Jne\Dni;
use Peru\Jne\DniParser;
use Peru\Sunat\Ruc;
use Peru\Sunat\HtmlParser;
use Peru\Sunat\RucParser;
use Peru\Http\ContextClient;


class consultaIdentidad extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function dniConsult($dni)
    {
        $cs = new Dni(new ContextClient(), new DniParser());

        $person = $cs->get($dni);
        if (!$person) {
            $estado = false;
            $totalVentasRealizadas  = 0;
            $numeroVentasRealizadas = 0;
        }else{
            $estado = true;
            $cliente = clientes::where('documento', $dni)
                                ->first();
            if($cliente){
                $totalVentasRealizadas  = ventas::where('cliente_id', $cliente->id)
                                            ->sum("total");
                $numeroVentasRealizadas = ventas::where('cliente_id', $cliente->id)
                                            ->count();
            }else{
                $totalVentasRealizadas  = 0;
                $numeroVentasRealizadas = 0;
            }
            

        }

        $rpta = array(
            'response'                  => $estado,
            'persona'                   => $person,
            'totalVentasRealizadas'     => $totalVentasRealizadas,
            'numeroVentasRealizadas'    => $numeroVentasRealizadas
        );
        echo json_encode($rpta);

    }

    public function rucConsult($ruc)
    {
        $cs = new Ruc(new ContextClient(), new RucParser(new HtmlParser()));

        $company = $cs->get($ruc);
        if (!$company) {
            $estado = false;
            $totalVentasRealizadas  = 0;
            $numeroVentasRealizadas = 0;
        }else{
            $estado = true;
            $cliente = clientes::where('documento', $ruc)
                                ->first();
            if($cliente){
                $totalVentasRealizadas  = ventas::where('cliente_id', $cliente->id)
                                            ->sum("total");
                $numeroVentasRealizadas = ventas::where('cliente_id', $cliente->id)
                                            ->count();
            }else{
                $totalVentasRealizadas  = 0;
                $numeroVentasRealizadas = 0;
            }
        }

        $rpta = array(
            'response'          => $estado,
            'persona'           => $company,
            'totalVentasRealizadas'     => $totalVentasRealizadas,
            'numeroVentasRealizadas'    => $numeroVentasRealizadas
        );
        echo json_encode($rpta);
    }

    
}
