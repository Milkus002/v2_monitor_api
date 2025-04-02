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
        schema::create('vsd_cars', function (Blueprint $table) {
            $table->id();
            $table->integer('id_vsd')
                ->constrained('vsds')
                ->onDelete('cascade');
            $table->integer('year');
            $table->string('car_type');
            $table->string('car_color');
            $table->string('brand');
            $table->string('model');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        schema::table('vsd_cars', function (Blueprint $table) {});
    }
};
