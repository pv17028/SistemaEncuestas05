<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEncuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encuestas', function (Blueprint $table) {
            $table->id('idEncuesta');
            $table->unsignedBigInteger('idUsuario');
            $table->string('titulo');
            $table->text('objetivo');
            $table->text('descripcionEncuesta');
            $table->string('grupoMeta');
            $table->date('fechaVencimiento');
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('idUsuario')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('encuestas');
    }
}
