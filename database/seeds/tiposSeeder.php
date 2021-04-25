<?php

use Illuminate\Database\Seeder;
use App\Tipos;

class tiposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Tipos::create([
    		'id'		=>	1000,
    		'nombre'	=>	'TEMPORAL'
    	]);
    }
}
