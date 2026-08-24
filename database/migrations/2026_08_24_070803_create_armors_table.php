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
        Schema::create('armors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('reading', 100)->nullable();
            $table->string('armor_type', 50);
            $table->string('weight_type', 50);
            $table->integer('weight');
            $table->integer('encumbrance_limit');
            $table->integer('price');
            $table->string('attribute', 50)->nullable();
            $table->integer('armor')->nullable();
            $table->integer('evasion_modifier')->nullable();
            $table->integer('movement_modifier')->nullable();
            $table->string('stealth', 50)->nullable();
            $table->integer('stealth_modifier')->nullable();
            $table->integer('skill_modifier')->nullable();
            $table->string('effect', 255)->nullable();
            $table->string('reference_page', 20)->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('armors');
    }
};
