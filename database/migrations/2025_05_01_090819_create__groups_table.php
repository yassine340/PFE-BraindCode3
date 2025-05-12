<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First, create the groups table
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('startup_id')->constrained('users')->onDelete('cascade');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });

        // Then, create the group_worker pivot table
        Schema::create('group_worker', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('worker_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            
            // Create a unique index to prevent duplicate entries
            $table->unique(['group_id', 'worker_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_worker');
        Schema::dropIfExists('groups');
    }
}