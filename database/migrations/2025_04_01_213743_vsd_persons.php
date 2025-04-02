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
        //
        Schema::create('vsd_persons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_vsd')
                    ->constrained('vsds')
                    ->onDelete('cascade');
            $table->string('upper_length', 50);
            $table->string('upper_color', 50);
            $table->string('skirt', 50);
            $table->string('shoulderbag', 50);
            $table->string('sex', 50);
            $table->string('mask', 50);
            $table->string('hat', 50);
            $table->string('glasses', 50);
            $table->string('backpack', 50);
            $table->string('age', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('vsd_persons');
    }
};
