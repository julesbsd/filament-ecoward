<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * @TODO: créer le trigger pour les points de l'utilisateur
     * lorsque qu'il termine un défi
     */
    
    public function up(): void
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('base_goal')->nullable();
            $table->integer('increment_goal')->nullable();
            $table->foreignId('trash_id')->nullable()->constrained('trashes');
            $table->foreignId('type_id')->constrained('challenge_types');
            $table->integer('points');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');

    }
};
