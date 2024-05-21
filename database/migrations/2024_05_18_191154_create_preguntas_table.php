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
        Schema::create('preguntas', function (Blueprint $table) {
            $table->id("idPregunta");
            $table->unsignedBigInteger('idTipoPregunta');
            $table-> string ('contenidoPregunta', 256);
            $table->string ('descripcionPregunta', 256);
            $table->string('criterioValidacion', 256);
            $table->integer ('posicionPregunta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preguntas');
    }
};
