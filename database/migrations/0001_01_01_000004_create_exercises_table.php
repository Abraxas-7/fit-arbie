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
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // Push, Pull, Legs, Core...
            $table->string('unit')->default('reps'); // reps, seconds
            $table->boolean('is_bodyweight')->default(false); // Se true, il peso è inteso come zavorra
            $table->boolean('is_assisted')->default(false); // Se true, la progressione efficace viene gestita inversamente
            $table->boolean('is_unilateral')->default(false); // Se true, permette di dividere i dati per lato
            $table->boolean('is_trackable_pr')->default(false); // Se true, viene tracciato nella hall of fame
            $table->text('description')->nullable(); // <-- NOTE FISSE (es. Settaggio macchina)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
