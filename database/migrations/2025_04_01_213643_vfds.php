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
        Schema::create('vfds' , function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_alarm')
                ->constrained('alarms')
                ->onDelete('cascade');
            $table->integer('id_target');
            $table->string('sex',50);
            $table->string('age', 25);
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
        Schema::dropIfExists('vfds');
    }
};
