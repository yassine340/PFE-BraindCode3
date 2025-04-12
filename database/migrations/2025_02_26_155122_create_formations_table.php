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
        Schema::create('formations', function (Blueprint $table) {
            $table->id();
            $table->string('titre')->nullable();
            $table->double('prix')->nullable();
            $table->boolean('estcertifiante')->nullable();
            $table->string('image_formation')->nullable(); 
            $table->string('est_valide')->default("EnAttente"); // Nouvelle colonne
            $table->boolean('est_publiee')->default(false); // Pour contrôler la publication
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formations');
    }
};
