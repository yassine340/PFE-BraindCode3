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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name'); // Prénom
            $table->string('last_name');  // Nom de famille
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable(); // Téléphone personnel
            $table->string('image_url')->nullable(); // URL de l'image de profil

            // Champs spécifiques aux startups
            $table->string('startup_name')->nullable();
            $table->string('code_fiscal')->nullable()->unique();
            $table->string('startup_email')->nullable()->unique();
            $table->string('startup_phone')->nullable();
            $table->string('speciality')->nullable(); // Spécialité du formateur ou startup
            $table->text('description')->nullable();

            // Ajout du champ rôle (sans valeur par défaut)
            $table->string('role')->nullable();

            // Ajouter un champ de statut pour la validation des formateurs
            $table->enum('status', ['en_attente', 'valide', 'rejete'])->default('en_attente')->nullable(); // Défaut à "en_attente" pour les formateurs en attente de validation

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
