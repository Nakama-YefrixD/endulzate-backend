<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCajaVentaIdToNotasCreditos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notasCreditos', function (Blueprint $table) {
            $table->unsignedBigInteger('cajaVenta_id')->nullable();
            $table->integer('estado')->nullable();

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
        Schema::table('notasCreditos', function (Blueprint $table) {
            //
        });
    }
}
