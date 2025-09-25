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
    Schema::table('products', function (Blueprint $table) {
        $table->renameColumn('name', 'nom');
        $table->renameColumn('category', 'categorie');
        $table->renameColumn('price', 'prix');
    });
}

public function down(): void
{
    Schema::table('products', function (Blueprint $table) {
        $table->renameColumn('nom', 'name');
        $table->renameColumn('categorie', 'category');
        $table->renameColumn('prix', 'price');
    });
}
};
