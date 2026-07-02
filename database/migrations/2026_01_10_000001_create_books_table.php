<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('author')->nullable();
            $table->string('country')->nullable();
            $table->string('age_group')->nullable();      // 3-5, 6-8, 9-12, 13-18
            $table->string('genre')->nullable();
            $table->string('reading_level')->nullable();
            $table->string('reading_time')->nullable();
            $table->string('category')->nullable();        // Nigerian Classics, African Classics, World Classics...
            $table->text('about')->nullable();
            $table->text('what_children_learn')->nullable();
            $table->string('themes')->nullable();
            $table->text('why_recommend')->nullable();
            $table->text('where_to_find')->nullable();      // library, publisher, bookstore links
            $table->string('purchase_url')->nullable();     // Amazon / bookstore
            $table->string('cover_image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_book_of_month')->default(false);
            $table->boolean('is_published')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['category','age_group','country']);
        });
    }
    public function down(): void { Schema::dropIfExists('books'); }
};
