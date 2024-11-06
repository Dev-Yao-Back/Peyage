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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('statut')->default('En Attente');

            $table->string('numero_vehcule');
            $table->string('poids1');
            $table->string('poids2')->nullable();
            $table->string('montant_paye')->nullable();
            $table->date('datepoids1')->nullable();  // Ajouter une valeur par défaut
            $table->date('datepoids2')->nullable();
            $table->string('heurepoids1')->nullable();
            $table->string('heurepoids2')->nullable();
            $table->string('poidsnet')->nullable();

            $table->string('type_operation')->nullable();
            $table->foreignId('transporteur_id')->constrained('transporteurs')->onDelete('cascade');
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->foreignId('peseur_id')->constrained('peseurs')->onDelete('cascade');


            $table->foreignId('provenance_id')->constrained('provenances')->onDelete('cascade');
            //$table->foreignId('destination_id')->constrained('destinations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');

  }
};