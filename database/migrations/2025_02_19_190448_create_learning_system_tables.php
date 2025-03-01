<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('author');
            $table->timestamps();
        });

        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('module_contents', function (Blueprint $table) { 
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->enum('content_type', ['text', 'image', 'video', 'code']);
            $table->text('content'); // Store text, image path, video link, or code
            $table->integer('position'); // Determines order of content in a chapter
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('module_contents');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('courses');
    }
};

