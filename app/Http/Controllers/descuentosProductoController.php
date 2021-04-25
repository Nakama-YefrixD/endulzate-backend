<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\descuentosProductos;

class descuentosProductoController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public static function index()
    {
        $descuentosProducto = descuentosProductos::all();
        return $descuentosProducto;        
    }
}
