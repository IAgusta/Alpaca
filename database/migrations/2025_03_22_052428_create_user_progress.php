<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration {
    public function up() {
        // Combined table for course
        Schema::create('user_course_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->integer('total_modules')->default(0);
            $table->integer('completed_modules')->default(0);
            $table->boolean('course_completed')->default(false);
            $table->timestamp('course_completed_at')->nullable();
            $table->timestamp('last_opened')->nullable();
            $table->timestamps();
        });

        Schema::create('user_module_progress', function(Blueprint $table){
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->boolean('read')->default(0);
            $table->timestamps();
        });

        // Table for content-level progress
        Schema::create('user_content_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_content_id')->constrained()->onDelete('cascade');
            $table->boolean('is_correct')->nullable();
            $table->string('selected_answer')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        // Drop tables in reverse order
        Schema::dropIfExists('user_content_progress');
        Schema::dropIfExists('user_course_progress');
    }
};