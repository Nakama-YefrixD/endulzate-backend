<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSucursales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sucursales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo', 45);
            $table->string('direccion', 45);
            $table->string('nombre', 45);
            $table->timestamps();
        });

        /*Schema::connection('mysql2')->create('sucursales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo', 45);
            $table->string('direccion', 45);
            $table->string('nombre', 45);
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sucursales');
    }
}
