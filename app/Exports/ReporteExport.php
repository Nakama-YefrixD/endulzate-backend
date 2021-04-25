<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReporteExport implements FromArray, WithHeadings, ShouldAutoSize, WithMapping
{
    protected $reporte;

    public function __construct(array $reporte)
    {
        $this->reporte = $reporte;
    }

    public function array(): array
    {
        return $this->reporte;
    }

    public function headings(): array
    {
        return [
        	'Sucursal',
        	'Fecha',
        	'Accion',
        	'Factura/Numero Boleta',
        	'Cantidad',
        	'Stock actual'
        ];
    }

    public function map($reporte): array
    {
        return [
            $reporte['sucursal'],
            $reporte['fecha'],
            $reporte['accion'],
            $reporte['registro'],
            $reporte['cantidad'],
            $reporte['stock'],
        ];
    }
}
