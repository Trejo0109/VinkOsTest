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
        Schema::create('visitantes', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->datetime('fecha_primera_visita');
            $table->datetime('fecha_ultima_visita');
            $table->unsignedBigInteger('visitas_totales')->default(1);
            $table->unsignedBigInteger('visitas_mes_actual')->default(1);
            $table->unsignedBigInteger('visitas_anio_actual')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitantes');
    }
};
