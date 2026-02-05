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
        Schema::create('workout_sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_exercise_id')->constrained()->cascadeOnDelete();

            $table->integer('set_number');
            $table->decimal('weight', 8, 2);
            $table->integer('reps');
            $table->string('type')->default('normal'); // warmup, failure, drop
            $table->boolean('is_completed')->default(false); // Questo ci serve per capire se è una serie che hai fatto davvero o se è solo "preparata" dal template
            $table->integer('rest_time')->nullable()->default(120);
            $table->string('side')->default('both'); // options: 'left', 'right', 'both'
            $table->integer('rest_unilateral')->nullable();
            $table->text('note')->nullable(); //Note per la singola serie (es: "Spotter ha aiutato")
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_sets');
    }
};
