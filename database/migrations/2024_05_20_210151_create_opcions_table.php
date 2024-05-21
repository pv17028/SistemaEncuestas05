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
        Schema::create('opcions', function (Blueprint $table) {
            $table->id("idOpcion");
            $table->unsignedBigInteger('idPregunta');
            $table->string('contenidoOpcion', 256);
            $table->integer('posicionOpcion');
            $table->foreign('idPregunta')->references('idPregunta')->on('preguntas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opcions');
    }
};
