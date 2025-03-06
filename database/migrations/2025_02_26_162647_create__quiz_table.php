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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lecon_id');
            $table->string('titre');
            $table->json('questions');
            $table->json('reponses');
            $table->integer('noteFinale');
            $table->integer('dureeMaximale');
            $table->timestamps();

            $table->foreign('lecon_id')->references('id')->on('lecons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
