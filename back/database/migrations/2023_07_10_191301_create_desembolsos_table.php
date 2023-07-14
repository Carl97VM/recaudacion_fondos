<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('desembolsos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_desembolso');
            $table->float('monto_desembolso',8,2);
            $table->float('monto_desembolso_gastado',8,2)->nullable();
            $table->integer('responsable');
            $table->unsignedBigInteger('candidato_id');
            $table->foreign('candidato_id')
                    ->references('id')
                    ->on('candidatos')
                    ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desembolsos');
    }
};
