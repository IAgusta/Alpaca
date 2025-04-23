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
            $table->string('theme')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->string('lock_password')->nullable();
            $table->integer('popularity')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('position');
            $table->timestamps();
        });

        Schema::create('module_contents', function (Blueprint $table) { 
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->enum('content_type', ['content','exercise']);
            $table->longText('content'); // Store text, image path, video link, or code
            $table->integer('position'); // Determines order of content in a chapter
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints(); // Disable FK checks before dropping

        Schema::dropIfExists('user_module_progress'); // Drop this first since it depends on `modules`
        Schema::dropIfExists('module_contents'); // Drop this before `modules`
        Schema::dropIfExists('modules'); // Now it's safe to drop
        Schema::dropIfExists('courses'); // Drop last since modules depend on courses
    
        Schema::enableForeignKeyConstraints();
    }
};

