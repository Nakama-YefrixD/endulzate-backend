<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrosCajas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrosCajas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sucursal_id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('cajaProducto_id');
            $table->integer('cantidad');
            $table->string('accion');
            $table->timestamps();

            $table->foreign('sucursal_id')->references('id')->on('sucursales');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->foreign('cajaProducto_id')->references('id')->on('productos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registrosCajas');
    }
}
