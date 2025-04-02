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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_alarm')
            ->constrained('alarms')
            ->onDelete('cascade');
            $table->string('plate_number', 50)->nullable();
            $table->string('id_car', 50)->nullable();
            $table->string('car_color', 50)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('plate_image', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('vehicles');
    }
};
