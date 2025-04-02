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
        schema::create('vsds', function (Blueprint $table) {
            $table->id();
            $table->integer('id_alarm')
                ->constrained('alarms')
                ->onDelete('cascade');
            $table->integer('id_event');
            $table->integer('id_target');
            $table->integer('id_target_type')
                ->constrained('target_types')
                ->onDelete('cascade');
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        schema::dropIfExists('vsds');
    }
};
