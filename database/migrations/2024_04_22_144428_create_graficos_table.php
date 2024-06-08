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
        Schema::create('graficos', function (Blueprint $table) {
            $table->id('idGrafico'); // PK
            $table->unsignedBigInteger('idResultadoEncuesta'); // FK
            $table->string('tipoGrafico', 20); // CHAR(20)
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('idResultadoEncuesta')->references('idResultadoEncuesta')->on('resultado_encuesta')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('graficos');
    }
};
