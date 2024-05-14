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
        Schema::create('bloqueo_usuarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('blocked_at');
            $table->timestamp('blocked_until')->nullable();
            $table->integer('block_duration')->nullable();
            $table->string('status')->default('active');
            $table->timestamp('unblocked_at')->nullable();
            $table->string('reason');
            $table->integer('temp_blocks')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bloqueo_usuarios');
    }
};