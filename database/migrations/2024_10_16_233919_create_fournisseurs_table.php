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
        Schema::create('fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom_fournisseur');
            $table->string('numero_fournisseur')->unique();


            $table->string('adresse_fournisseur')->nullable();
            $table->string('telephone_fournisseur')->nullable();
            $table->string('email_fournisseur')->nullable();

            // En unités ex: tonnes
            $table->integer('capacite_production_mensuelle')->nullable(); // Ex: tonnes par mois


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fournisseurs');
    }
};
