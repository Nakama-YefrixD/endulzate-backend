<?php

namespace App\Http\Controllers\Versiondos\CargarData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\User;
use App\marcas;
use App\Tipos;
use App\productos;
use App\sucursales;
use App\almacenes;

class OtroAlmacenController extends Controller
{
    public function cargar(Request $request)
    {
        echo "otros";

    }
}
