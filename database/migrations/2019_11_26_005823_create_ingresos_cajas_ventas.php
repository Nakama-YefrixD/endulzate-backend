<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngresosCajasVentas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingresosCajasVentas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cajaVenta_id');
            $table->string('ingreso');
            $table->string('motivo');
            $table->timestamps();

            $table->foreign('cajaVenta_id')->references('id')->on('cajasVentas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingresosCajasVentas');
    }
}
