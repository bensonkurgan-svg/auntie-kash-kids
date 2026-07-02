<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_key')->unique();
            $table->json('content');
            $table->enum('status', ['DRAFT','PENDING_REVIEW','APPROVED','REJECTED'])->default('DRAFT');
            $table->integer('version')->default(1);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('cms_pages'); }
};
