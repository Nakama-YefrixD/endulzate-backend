<?php

use Illuminate\Database\Seeder;
use App\tiposMonedas;

class tiposMonedasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tiposMonedas::create([
            'codigo' => '1',
            'simbolo' => 'S/',
            'abreviatura' => 'PEN',
            'nombre' => 'NUEVOS SOLES',
        ]);

        tiposMonedas::create([
            'codigo' => '2',
            'simbolo' => '$',
            'abreviatura' => 'USD',
            'nombre' => 'DÓLARES AMERICANOS',
        ]);
    }
}
