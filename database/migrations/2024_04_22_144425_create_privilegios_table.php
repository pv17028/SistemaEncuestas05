<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrivilegiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('privilegios', function (Blueprint $table) {
            $table->id('idPrivilegio');
            $table->string('nombrePrivilegio', 50);
            $table->string('descripcionPrivilegio', 256)->nullable();
            $table->timestamps();
        });

        Schema::create('rol_privilegio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idRol')->constrained('rol', 'idRol');
            $table->foreignId('idPrivilegio')->constrained('privilegios', 'idPrivilegio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('privilegios');
        Schema::dropIfExists('rol_privilegio');
    }
}
