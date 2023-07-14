<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidatos', function (Blueprint $table) {
            $table->id();
            $table->string('ci');
            $table->string('nombres',100);
            $table->string('apellidos',100);
            $table->string('celular',100);
            $table->string('nombre_candidatura',100);
            // $table->unsignedBigInteger('aportes_id');
            // $table->foreign('aportes_id')
            //         ->references('id')
            //         ->on('aportes')
            //         ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidatos');
    }
}
