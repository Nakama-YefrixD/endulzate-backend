<?php

use Illuminate\Database\Seeder;
use App\marcas;

class marcasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        marcas::create([
        	'id'		=>	1000,
        	'nombre'	=>	'TEMPORAL',
      	]);
    }
}
