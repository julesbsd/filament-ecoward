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
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('action_type_id')->constrained('action_types');
            $table->foreignId('challenge_id')->constrained('challenges')->nullable();
            $table->foreignId('trash_id')->constrained('trashes');
            $table->foreignId('user_id')->constrained('users');
            $table->integer('quantity')->default(1);
            $table->string('status');
            $table->string('description')->nullable();
            $table->string('image_action');
            $table->string('image_throw')->nullable();
            $table->string('location');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions');
    }
};
