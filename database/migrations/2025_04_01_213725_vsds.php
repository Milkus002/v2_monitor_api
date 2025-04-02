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
        Schema::create('vsds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_alarm')
                ->nullable()
                ->constrained('alarms')
                ->onDelete('cascade');
            $table->integer('id_event');
            $table->integer('id_target');
            $table->foreignId('id_target_type')
                ->nullable()
                ->constrained('target_types')
                ->onDelete('cascade');
            $table->string('image', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('vsds');
    }
};
