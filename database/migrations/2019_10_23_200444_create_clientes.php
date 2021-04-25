<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tipoDocumento_id');
            $table->bigInteger('documento');
            $table->string('nombre');
            $table->string('telefono')->nullable(); 
            $table->string('direccion')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();

            $table->foreign('tipoDocumento_id')->references('id')->on('tiposDocumentos');
        });

        /*Schema::connection('mysql2')->create('clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tipoDocumento_id')->nullable();
            $table->bigInteger('documento');
            $table->string('nombre');
            $table->string('telefono')->nullable(); 
            $table->string('direccion')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();

            $table->foreign('tipoDocumento_id')->references('id')->on('tiposdocumentos');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
