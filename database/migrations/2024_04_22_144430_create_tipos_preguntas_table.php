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
        Schema::create('tipo_preguntas', function (Blueprint $table) {
            $table->id('idTipoPregunta');
            $table->string('nombreTipoPregunta', 50);
            $table->string('descripcionTipoPregunta', 300);
            $table->boolean('habilitado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_preguntas');
    }
};