<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('adresses', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 50);
            $table->string('rue', 150);
            $table->string('ville', 100);
            $table->string('code_postal', 20);
            $table->string('pays', 100);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('adresses');
    }
};
