<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('titre')->nullable(); // Nom de la vidéo
            $table->string('url')->nullable(); // Chemin ou URL de la vidéo stockée
            $table->foreignId('formation_id')->constrained()->onDelete('cascade')->nullable(); // Relation avec formations
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('videos');
    }
};
