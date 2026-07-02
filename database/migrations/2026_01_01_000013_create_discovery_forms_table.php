<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('discovery_forms', function (Blueprint $table) {
            $table->id();
            $table->string('parent_name');
            $table->string('parent_email');
            $table->string('parent_phone')->nullable();
            $table->string('parent_country')->nullable();
            $table->string('parent_city')->nullable();
            $table->string('preferred_contact')->default('Email');
            $table->string('child_name');
            $table->integer('child_age');
            $table->string('child_grade')->nullable();
            $table->string('child_country')->nullable();
            $table->string('primary_language')->nullable();
            $table->json('interests')->nullable();
            $table->json('strengths')->nullable();
            $table->json('skills_to_develop')->nullable();
            $table->json('learning_preferences')->nullable();
            $table->text('parent_goals')->nullable();
            $table->json('preferred_days')->nullable();
            $table->string('preferred_time')->nullable();
            $table->string('time_zone')->nullable();
            $table->boolean('wants_recommendation')->default(true);
            $table->string('hear_about_us')->nullable();
            $table->enum('status', ['NEW','CONTACTED','ENROLLED'])->default('NEW');
            $table->string('assigned_to')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('discovery_forms'); }
};
