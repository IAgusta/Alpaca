<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('robots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('api_key')->unique()->nullable();
            $table->timestamp('api_key_last_reset')->nullable();
            $table->string('command')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });

        Schema::create('robot_detail', function (Blueprint $table){
            $table->id();
            $table->foreignId('robot_id')->unique()->constrained()->onDelete('cascade');
            $table->string('robot_image')->nullable();
            $table->enum('controller', ['ESP32', 'ESP8266']);
            $table->json('components')->nullable();
            $table->boolean('isPublic')->default(true);
            $table->timestamps();
        });

        Schema::create('robot_sensor_logs', function (Blueprint $table){
            $table->id();
            $table->foreignId('robot_id')->constrained()->onDelete('cascade');
            $table->string('sensor_type');
            $table->string('value');
            $table->timestamps();
        });

        Schema::create('make_control', function(Blueprint $table){
            $table->id()->unique();
            $table->string('author');
            $table->string('name');
            $table->json('design');
            $table->string('command');
            $table->boolean('isPublic')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('control_library', function(Blueprint $table){
            $table->id()->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('make_control_id')->constrained()->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('robot');
        Schema::dropIfExists('robot_detail');
        Schema::dropIfExists('robot_sensor_logs');
        Schema::dropIfExists('make_control');
        Schema::dropIfExists('control_library');
    }
};
