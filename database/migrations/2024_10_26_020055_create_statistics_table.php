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
        Schema::create('estadisticas', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('jyv')->nullable();
            $table->string('badmail')->nullable();
            $table->string('baja')->nullable();
            $table->dateTime('fecha_envio')->nullable();
            $table->dateTime('fecha_open')->nullable();
            $table->integer('opens')->nullable();
            $table->integer('opens_virales')->nullable();
            $table->dateTime('fecha_click')->nullable();
            $table->integer('clicks')->nullable();
            $table->integer('clicks_virales')->nullable();
            $table->string('links')->nullable();
            $table->string('ips')->nullable();
            $table->string('navegadores')->nullable();
            $table->string('plataformas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estadisticas');
    }
};
