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
        Schema::create('provenances', function (Blueprint $table) {
            $table->id();
            $table->string('nom_provenance');
            $table->string('numero_provenance')->unique();
            $table->string('adresse_provenance');
            $table->string('region_provenance');
            $table->string('ville_provenance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provenances');
    }
};
