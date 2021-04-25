<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCajasVentas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cajasVentas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sucursal_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('numero');
            $table->dateTime('apertura')->nullable();
            $table->dateTime('cierre')->nullable();
            $table->string('totalApertura')->nullable();
            $table->string('totalAperturo')->nullable();
            $table->string('totalCierre')->nullable();
            $table->string('totalCerro')->nullable();
            $table->string('totalVentasTarjeta')->nullable();
            $table->string('totalVentasEfectivo')->nullable();
            $table->string('totalVentas')->nullable();
            $table->string('totalVentasCanceladas')->nullable();
            $table->string('totalGastos')->nullable();
            $table->integer('numeroVentasTarjeta')->nullable();
            $table->integer('numeroVentasEfectivo')->nullable();
            $table->integer('numeroVentas')->nullable();
            $table->integer('numeroGastos')->nullable();
            $table->integer('numeroItems')->nullable();
            $table->integer('numeroVentasCanceladas')->nullable();
            $table->integer('numeroItemsCancelados')->nullable();
            
            $table->timestamps();

            $table->foreign('sucursal_id')->references('id')->on('sucursales');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cajasVentas');
    }
}
