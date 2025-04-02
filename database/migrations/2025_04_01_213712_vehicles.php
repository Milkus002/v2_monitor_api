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
        schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->integer('id_alarm')
            ->constrained('alarms')
            ->onDelete('cascade');
            $table->string('plate_number');
            $table->string('id_car');
            $table->string('car_color');
            $table->string('image');
            $table->string('plate_image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        schema::dropIfExists('vehicles');
    }
};
