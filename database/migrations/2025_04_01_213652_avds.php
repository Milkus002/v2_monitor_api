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
        Schema::create('avds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_alarm')
                ->constrained('alarms')
                ->onDelete('cascade');
            $table->integer('id_event');
            $table->string('status', 50);
            $table->string('alarm_type', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('avds');
    }
};
