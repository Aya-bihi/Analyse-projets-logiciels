<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('erreurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('analyse_id')->constrained()->onDelete('cascade');
            $table->foreignId('categorie_id')->constrained('erreur_categories');
            $table->string('fichier');
            $table->integer('ligne')->nullable();
            $table->text('description');
            $table->enum('gravite', ['faible', 'moyenne', 'critique']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('erreurs');
    }
};
