<?php

use Illuminate\Database\Seeder;
use App\sucursales;

class sucursalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        sucursales::create([
        	'id' => 1,
        	'codigo' => '001',
        	'direccion' => 'PRUEBA',
        	'nombre' => 'Endulzate1',
        ]);

        sucursales::create([
        	'id' => 2,
        	'codigo' => '002',
        	'direccion' => 'Plaza de las americas',
        	'nombre' => 'Endulzate2',
        ]);
    }
}
