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
        Schema::create('object_countings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_general')
                ->constrained('generals')
                ->onDelete('cascade');
            $table->string('object_type');
            $table->string('object_state');
            $table->integer('count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('object_countings');
    }
};
