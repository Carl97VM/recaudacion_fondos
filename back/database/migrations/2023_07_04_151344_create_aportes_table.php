<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aportes', function (Blueprint $table) {
            $table->id();
            $table->text('tipo_aporte')->nullable();
            $table->date('fecha_inicio_aporte');
            $table->date('fecha_fin_aporte');
            $table->text('descripcion')->nullable();
            $table->string('archivo', 200);
            $table->integer('usuario_id');
            $table->unsignedBigInteger('aportantes_id');
            $table->foreign('aportantes_id')
                    ->references('id')
                    ->on('aportantes')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('aportes');
    }
}
