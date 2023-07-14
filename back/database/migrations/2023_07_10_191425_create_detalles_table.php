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
        Schema::create('detalles', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion',200);
            $table->float('monto_detalle',8,2);
            $table->unsignedBigInteger('rendicion_cuentas_id');
            $table->foreign('rendicion_cuentas_id')
                    ->references('id')
                    ->on('rendicion__cuentas')
                    ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles');
    }
};
