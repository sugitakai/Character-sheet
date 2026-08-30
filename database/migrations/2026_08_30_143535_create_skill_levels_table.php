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
        Schema::create('skill_levels', function (Blueprint $table) {
            $table->id();
            // skills.id への外部キー
            $table->foreignId('skill_id')->constrained('skills')->onDelete('cascade');//skill_id（skills.id）
            $table->string('level'); //level（初歩・習熟・熟練・達人・伝説）
            $table->string('prerequisite', 200); //prerequisite（その段階に進むための条件）
            $table->text('effect')->nullable(); //effect（その段階の効果文章）
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_levels');
    }
};
