<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('theme')->nullable();          // Animals, Pirates, Grammar, Financial Literacy...
            $table->string('age_bracket')->nullable();     // 3-7, 8-12, 9-16
            $table->string('activity_type')->nullable();   // Word Search, Crossword, Maze, I Spy...
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();      // preview photo
            $table->string('file_path')->nullable();       // downloadable PDF
            $table->boolean('is_published')->default(true);
            $table->integer('sort_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['theme','age_bracket']);
        });
    }
    public function down(): void { Schema::dropIfExists('activities'); }
};
