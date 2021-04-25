<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransferencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transferencias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('origen');
            $table->unsignedBigInteger('destino');
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('cajaVentaOrigen_id')->nullable();
            $table->unsignedBigInteger('cajaVentaDestino_id')->nullable();
            $table->unsignedBigInteger('userOrigen_id')->nullable();
            $table->unsignedBigInteger('userDestino_id')->nullable();
            $table->integer('cantidad');
            $table->text('motivo')->nullable();
            $table->integer('estado')->default(0); //estado 0 se envio, estado 1 se recibio
            $table->integer('antesOrigenCantidad');
            $table->integer('antesDestinoCantidad');
            $table->integer('despuesOrigenCantidad');
            $table->integer('despuesDestinoCantidad');
            $table->dateTime('fechaEnvio')->nullable();
            $table->dateTime('fechaRecibido')->nullable();

            $table->timestamps();

            $table->foreign('origen')->references('id')->on('sucursales');
            $table->foreign('destino')->references('id')->on('sucursales');
            $table->foreign('producto_id')->references('id')->on('productos');
            $table->foreign('cajaVentaOrigen_id')->references('id')->on('cajasVentas');
            $table->foreign('cajaVentaDestino_id')->references('id')->on('cajasVentas');
            $table->foreign('userOrigen_id')->references('id')->on('users');
            $table->foreign('userDestino_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transferencias');
    }
}
