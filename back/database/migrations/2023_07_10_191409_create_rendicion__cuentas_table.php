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
        Schema::create('rendicion__cuentas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_rendicion');
            $table->float('monto_rendicion',8,2);
            $table->unsignedBigInteger('desembolso_id');
            $table->foreign('desembolso_id')
                    ->references('id')
                    ->on('desembolsos')
                    ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rendicion__cuentas');
    }
};
