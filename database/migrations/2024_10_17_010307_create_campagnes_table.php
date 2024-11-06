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
        Schema::create('campagnes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable(); // Description de la campagne
            $table->date('date_debut'); // Date de début de la campagne
            $table->date('date_fin')->nullable(); // Date de fin (optionnel si la campagne est indéterminée)
            $table->decimal('reduction', 5, 2)->nullable(); // Pourcentage de réduction ou remise
            $table->foreignId('entreprise_id')->constrained('entreprises')->onDelete('cascade'); // Référence à l'entreprise de péage
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campagnes');
    }
};
