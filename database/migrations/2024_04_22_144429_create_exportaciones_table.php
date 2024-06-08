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
        Schema::create('exportaciones', function (Blueprint $table) {
            $table->id('idExportar'); //PK
            $table->unsignedBigInteger('idGrafico');
            $table->unsignedBigInteger('idResultadoEncuesta'); // FK
            $table->string('tipoExportacion', 15); // CHAR(15)
            $table->timestamps();

            //FK constraints
            $table->foreign('idGrafico')->references('idGrafico')->on('graficos')->onDelete('cascade');
            $table->foreign('idResultadoEncuesta')->references('idResultadoEncuesta')->on('resultado_encuesta')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exportaciones');
    }
};
