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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player1_id')->constrained('users');
            $table->foreignId('player2_id')->nullable()->constrained('users');
            $table->integer('score1')->default(0);
            $table->integer('score2')->default(0);
            $table->integer('player1_180s')->default(0);
            $table->integer('player2_180s')->default(0);
            $table->float('player1_checkout_percentage', 5, 2)->default(0.00);
            $table->float('player2_checkout_percentage', 5, 2)->default(0.00);
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->foreignId('tournament_id')->nullable()->constrained('tournaments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
