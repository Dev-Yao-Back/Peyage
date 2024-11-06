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
        Schema::create('entreprises', function (Blueprint $table) {
            $table->id();
            $table->string('nom');

            $table->string('adresse')->nullable();
            $table->string('logo')->nullable();

            $table->string('ville')->nullable();
            $table->string('region')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('site_web')->nullable();
            $table->date('date_creation')->nullable();
            $table->integer('capacite')->default(0); // Capacité du pont (nombre de véhicules/jour)

            $table->integer('nombre_voies')->default(0); // Nombre de voies du péage
            $table->string('horaires_ouverture')->nullable();
            $table->string('responsable_gestion')->nullable();
            $table->string('types_paiement_acceptes')->nullable(); // Moyens de paiement acceptés
            $table->string('entretien_maintenance')->nullable(); // Société responsable de l'entretien
            $table->string('statut_juridique')->nullable();
            $table->string('coordonnees_gps')->nullable(); // Référence géographique (GPS)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entreprises');
    }
};
