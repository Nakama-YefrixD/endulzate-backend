<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(sucursalesSeeder::class);
        $this->call(tiposComprobantesSeeder::class);
        $this->call(tiposDocumentosSeeder::class);
        $this->call(tiposMonedasSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(marcasSeeder::class);
        $this->call(tiposSeeder::class);
        $this->call(tiposCajasProductosSeeder::class);
        $this->call(usuarioSeeder::class);
        $this->call(usuariosSucursalesSeeder::class);
    }
}
