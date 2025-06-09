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
        Schema::create('robot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('api_key')->unique()->nullable();
            $table->timestamp('api_key_last_reset')->nullable();
            $table->string('command')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();
        });

        Schema::create('robot_sensor_logs', function (Blueprint $table){
            $table->id();
            $table->foreignId('robot_id')->constrained()->onDelete('cascade');
            $table->string('sensor_type');
            $table->string('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('robot');
        Schema::dropIfExists('robot_sensor_logs');
    }
};
