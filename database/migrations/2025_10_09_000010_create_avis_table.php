<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvisTable extends Migration
{
    /**
     * Exécute la migration.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Lien avec la table des utilisateurs
            $table->foreignId('puzzle_id')->constrained()->onDelete('cascade'); // Lien avec la table des puzzles
            $table->text('commentaire'); // Commentaire de l'avis
            $table->integer('note')->unsigned(); // Note de l'avis (par exemple de 1 à 5)
            $table->timestamps();
        });
    }

    /**
     * Annule la migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avis');
    }
}
