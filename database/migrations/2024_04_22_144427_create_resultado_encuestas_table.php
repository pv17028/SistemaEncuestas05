<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('resultado_encuesta', function (Blueprint $table) {
            $table->id('idResultadoEncuesta'); // PK
            $table->unsignedBigInteger('idEncuesta'); // FK
            $table->date('fechaResultados'); // DATE
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('idEncuesta')->references('idEncuesta')->on('encuestas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resultados_encuesta');
    }
};
