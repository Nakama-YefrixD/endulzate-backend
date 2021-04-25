<?php
use Illuminate\Database\Seeder;
use App\tiposDocumentos;
class tiposDocumentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tiposDocumentos::create([
            'codigo' => '1',
            'abreviacion' => 'DNI',
            'nombre' => 'DOCUMENTO NACIONAL DE IDENTIDAD',
        ]);
        tiposDocumentos::create([
            'codigo' => '4',
            'abreviacion' => 'CARNET DE EXTRANJERIA',
            'nombre' => 'CARNET DE EXTRANJERIA',
        ]);
        tiposDocumentos::create([
            'codigo' => '6',
            'abreviacion' => 'RUC',
            'nombre' => 'REGISTRO ÚNICO DE CONTRIBUYENTES',
        ]);
        tiposDocumentos::create([
            'codigo' => '0',
            'abreviacion' => 'OTRO',
            'nombre' => 'NO INDICADO',
        ]);
    }
}