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
        Schema::create('vsd_cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_vsd')
                ->constrained('vsds')
                ->onDelete('cascade');
            $table->integer('year');
            $table->string('car_type', 50);
            $table->string('car_color', 50);
            $table->string('brand', 50);
            $table->string('model' ,50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('vsd_cars');
    }
};
