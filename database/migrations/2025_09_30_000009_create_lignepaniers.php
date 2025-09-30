<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lignepaniers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('puzzle_id')->constrained('puzzles')->cascadeOnDelete();
            $table->foreignId('panier_id')->constrained('paniers')->cascadeOnDelete();
            $table->integer('quantite')->default(1);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('lignepaniers');
    }
};
