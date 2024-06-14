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
            $table->dateTime('fechaVencimiento');
            $table->boolean('compartida')->default(false); 
            $table->boolean('compartirConTodos')->default(false); 
            $table->text('compartida_con')->nullable(); 
            $table->boolean('es_anonima')->default(false); 
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