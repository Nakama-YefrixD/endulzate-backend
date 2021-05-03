<?php

use Illuminate\Database\Seeder;
use App\usuariosSucursales;

class usuariosSucursalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        usuariosSucursales::create([
            "sucursal_id"    => 1,
            "user_id"        => 1,
            "predeterminado" => 1
        ]);

        usuariosSucursales::create([
            "sucursal_id"    => 1,
            "user_id"        => 2,
            "predeterminado" => 1
        ]);

        usuariosSucursales::create([
            "sucursal_id"    => 2,
            "user_id"        => 2,
            "predeterminado" => 1
        ]);
    }
}
