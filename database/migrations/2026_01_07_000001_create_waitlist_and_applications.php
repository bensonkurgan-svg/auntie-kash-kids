<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        // Waitlist signups (families interested in future programs)
        Schema::create('waitlist_entries', function (Blueprint $table) {
            $table->id();
            $table->string('parent_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('child_name')->nullable();
            $table->integer('child_age')->nullable();
            $table->string('program_interest')->nullable();
            $table->enum('type', ['WAITLIST','FOUNDING_FAMILY'])->default('WAITLIST');
            $table->text('message')->nullable();
            $table->enum('status', ['NEW','CONTACTED','ENROLLED'])->default('NEW');
            $table->timestamps();
        });

        // Instructor applications (Become an Instructor)
        Schema::create('instructor_applications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('qualifications')->nullable();
            $table->text('experience')->nullable();
            $table->string('subjects')->nullable();
            $table->string('cv_path')->nullable();
            $table->text('cover_note')->nullable();
            $table->enum('status', ['NEW','REVIEWING','SHORTLISTED','REJECTED','HIRED'])->default('NEW');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('waitlist_entries');
        Schema::dropIfExists('instructor_applications');
    }
};
