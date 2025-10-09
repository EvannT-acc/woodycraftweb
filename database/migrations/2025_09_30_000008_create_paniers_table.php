<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('paniers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('status')->default(0); // 0 = en cours, 1 = payé…
            $table->decimal('total', 10, 2)->default(0);
            $table->boolean('mode_paiement')->nullable(); // true = carte, false = autre
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('paniers');
    }
};
