<?php

use Illuminate\Database\Seeder;
use App\tiposComprobantes;

class tiposComprobantesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tiposComprobantes::create([
            'sucursal_id' => 1,
            'codigo' => '01',
            'serie' => 'F001',
            'nombre' => 'FACTURA',
	       'correlativo' => 1,
        ]);

        tiposComprobantes::create([
            'sucursal_id' => 1,
            'codigo' => '03',
            'serie' => 'B001',
            'nombre' => 'BOLETA DE VENTA',
	       'correlativo' => 1,
        ]);

        tiposComprobantes::create([
            'sucursal_id' => 1,
            'codigo' => '71',
            'serie' => 'V001',
            'nombre' => 'VENTAS INTERNAS',
           'correlativo' => 2,
        ]);
    }
}
