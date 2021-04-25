<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tiposMonedas;

class TiposMonedaController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public static function index()
    {
        $tiposMoneda = tiposMonedas::all();
        return $tiposMoneda;        
    }
}
