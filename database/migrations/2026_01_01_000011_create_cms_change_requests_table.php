<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('cms_change_requests', function (Blueprint $table) {
            $table->id();
            $table->string('page_key');
            $table->json('changes');
            $table->enum('status', ['PENDING','APPROVED','REJECTED'])->default('PENDING');
            $table->foreignId('requested_by')->constrained('users');
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->text('review_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('cms_change_requests'); }
};
