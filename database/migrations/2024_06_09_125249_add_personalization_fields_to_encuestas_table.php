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
        Schema::table('encuestas', function (Blueprint $table) {
            $table->string ('logo')->nullable()->after('compartida_con');
            $table->string ('color_principal')->nullable()->after('logo');
            $table->string ('color_secundario')->nullable()->after('color_principal');
            $table->string('color_terciario')->nullable()->after('color_secundario');
            $table->string('color_cuarto')->nullable()->after('color_terciario');
            $table->string('color_quinto')->nullable()->after('color_cuarto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('encuestas', function (Blueprint $table) {
            $table->dropColumn('logo');
            $table->dropColumn('color_principal');
            $table->dropColumn('color_secundario');
            $table->dropColumn('color_terciario');
            $table->dropColumn('color_cuarto');
            $table->dropColumn('color_quinto');
        });
    }
};
