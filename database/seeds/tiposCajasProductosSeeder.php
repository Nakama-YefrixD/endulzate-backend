<?php

use Illuminate\Database\Seeder;
use App\tiposCajasProductos;

class tiposCajasProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tiposCajasProductos::create([
            'id'            => 1,
            'descripcion'   => 'NO TIENE CAJA',
        ]);

        tiposCajasProductos::create([
            'id'            => 2,
            'descripcion'   => 'ES EL PRODUCTO DE UNA CAJA',
        ]);

        tiposCajasProductos::create([
            'id'            => 3,
            'descripcion'   => 'ES UNA CAJA',
        ]);
    }
}
