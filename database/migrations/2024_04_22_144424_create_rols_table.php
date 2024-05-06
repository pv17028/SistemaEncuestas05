<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rol', function (Blueprint $table) {
            $table->id('idRol');
            $table->string('nombreRol', 50);
            $table->string('descripcionRol', 256);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('idRol')->references('idRol')->on('rol');
        });

        Schema::create('privilegio', function (Blueprint $table) {
            $table->id('idPrivilegio');
            $table->unsignedBigInteger('idRol');
            $table->string('nombrePrivilegio', 50);
            $table->string('descripcionPrivilegio', 256);
            $table->timestamps();

            $table->foreign('idRol')->references('idRol')->on('rol');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('privilegio');
        Schema::dropIfExists('rol');
    }
}
