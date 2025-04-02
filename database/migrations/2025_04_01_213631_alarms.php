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
        Schema::create('alamrs' , function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_device')
                ->constrained('devices')
                ->onDelete('cascade');
            $table->foreignId('id_type')
                ->constrained('alarm_types')
                ->onDelete('cascade');
            $table->bigInteger('utc');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('alamrs');
    }
};
