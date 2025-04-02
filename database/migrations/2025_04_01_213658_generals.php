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
        Schema::create('generals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_alarm')
                ->constrained('alarms')
                ->onDelete('cascade');
            $table->integer('id_event')->nullable();
            $table->integer('id_target')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('image', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        schema::dropIfExists('generals');
    }
};
