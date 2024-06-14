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
        Schema::create('encuesta_usuario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encuesta_id')->constrained('encuestas', 'idEncuesta');
            $table->foreignId('usuario_id')->nullable()->constrained('users', 'id');
            $table->json('respuesta_ids')->nullable();
            $table->json('preguntas_no_respondidas')->nullable();
            $table->boolean('completa')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encuesta_usuario');
    }
};
