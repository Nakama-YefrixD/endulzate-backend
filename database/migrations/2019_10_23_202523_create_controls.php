<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateControls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('controls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('metodo');
            $table->string('tabla');
            $table->text('campos');
            $table->text('datos');
            $table->text('descripcion')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });

        /*Schema::connection('mysql2')->create('controls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('metodo');
            $table->string('tabla');
            $table->text('campos');
            $table->text('datos');
            $table->text('descripcion')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('controls');
    }
}
