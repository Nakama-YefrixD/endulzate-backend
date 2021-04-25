<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tipoComprobante_id');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tipoMoneda_id');
            $table->unsignedBigInteger('sucursal_id');
            $table->integer('numero');
            $table->date('fecha');
            $table->date('fechaVencimiento');
            $table->string('Observaciones', 500);
            $table->string('descuento')->nullable();
            $table->integer('igv');
            $table->string('impuestos');
            $table->string('subtotal');
            $table->string('total');
            $table->boolean('estadoEmail');
            $table->boolean('estadoSunat');
            $table->string('xml')->nullable();
            $table->string('cdr')->nullable();
            $table->string('pdf')->nullable();
            $table->timestamps();

            $table->foreign('tipoComprobante_id')->references('id')->on('tiposComprobantes');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('tipoMoneda_id')->references('id')->on('tiposMonedas');
            $table->foreign('sucursal_id')->references('id')->on('sucursales');
        });

        /*Schema::connection('mysql2')->create('ventas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tipoComprobante_id')->nullable();
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('tipoMoneda_id')->nullable();
            $table->integer('numero');
            $table->date('fecha');
            $table->date('fechaVencimiento');
            $table->string('Observaciones');
            $table->string('descuento')->nullable();
            $table->integer('igv');
            $table->string('impuestos');
            $table->string('subtotal');
            $table->string('total');
            $table->boolean('estadoEmail');
            $table->boolean('estadoSunat');
            $table->string('xml')->nullable();
            $table->string('cdr')->nullable();
            $table->string('pdf')->nullable();
            $table->timestamps();

            $table->foreign('tipoComprobante_id')->references('id')->on('tiposComprobantes');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('tipoMoneda_id')->references('id')->on('tiposMonedas');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas');
    }
}
