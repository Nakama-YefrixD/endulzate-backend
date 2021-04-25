<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiposComprobantes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tiposComprobantes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sucursal_id');
            $table->char('codigo', 2);
            $table->string('serie');
            $table->integer('correlativo');
            $table->string('nombre');
            $table->timestamps();

            $table->foreign('sucursal_id')->references('id')->on('sucursales');
        });

        /*Schema::connection('mysql2')->create('tiposComprobantes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('codigo', 2);
            $table->string('serie');
            $table->integer('correlativo');
            $table->string('nombre');
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
        Schema::dropIfExists('tiposComprobantes');
    }
}
