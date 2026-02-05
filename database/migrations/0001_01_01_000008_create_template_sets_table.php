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
        Schema::create('template_sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_exercise_id')->constrained()->cascadeOnDelete();
            $table->integer('set_number');
            $table->string('type')->default('normal'); // warmup, failure, drop
            $table->integer('target_reps')->nullable();
            $table->decimal('target_weight', 8, 2)->nullable();
            $table->integer('rest_time')->default(120);
            $table->string('side')->default('both'); // options: 'left', 'right', 'both'
            $table->integer('rest_unilateral')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_sets');
    }
};
