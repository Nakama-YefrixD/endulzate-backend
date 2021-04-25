<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('marca_id');
            $table->unsignedBigInteger('tipo_id');
            $table->string('codigo');
            $table->string('nombre');
            $table->bigInteger('total');
            $table->bigInteger('vendido');
            $table->integer('cantidad');
            $table->string('precio');
            $table->timestamps();
            
            $table->foreign('marca_id')->references('id')->on('marcas');
            $table->foreign('tipo_id')->references('id')->on('tipos');
        });

        /*Schema::connection('mysql2')->create('productos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('marca_id')->nullable();
            $table->unsignedBigInteger('tipo_id')->nullable();
            $table->string('codigo');
            $table->string('nombre');
            $table->bigInteger('total');
            $table->bigInteger('vendido');
            $table->integer('cantidad');
            $table->string('precio');
            $table->timestamps();
            
            $table->foreign('marca_id')->references('id')->on('marcas');
            $table->foreign('tipo_id')->references('id')->on('tipos');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
