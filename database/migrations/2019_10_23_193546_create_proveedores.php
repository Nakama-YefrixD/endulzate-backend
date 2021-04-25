<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProveedores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->bigInteger('ruc')->nullable();
            $table->bigInteger('numero')->nullable();
            $table->string('direccion', 100)->nullable();
            $table->string('tipo')->nullable();
            $table->timestamps();
        });

        /*Schema::connection('mysql2')->create('proveedores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->bigInteger('ruc')->nullable();
            $table->bigInteger('numero')->nullable();
            $table->string('direccion', 100)->nullable();
            $table->string('tipo')->nullable();
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
        Schema::dropIfExists('proveedores');
    }
}
