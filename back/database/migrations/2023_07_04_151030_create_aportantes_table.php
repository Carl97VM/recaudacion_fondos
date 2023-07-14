<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAportantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aportantes', function (Blueprint $table) {
            $table->id();
            $table->string('ci',12);
            $table->string('nombres',100);
            $table->string('apellidos',100);
            $table->string('celular',100);
            $table->string('profesion_oficio',100);
            $table->string('ciudad',100);
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
        Schema::dropIfExists('aportantes');
    }
}
