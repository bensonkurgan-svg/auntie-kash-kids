<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('must_change_password')->default(false)->after('role');
            $table->boolean('is_active')->default(true)->after('must_change_password');
            $table->string('phone')->nullable()->after('is_active');
            $table->string('work_email')->nullable()->after('phone');
            $table->string('photo_url')->nullable()->after('work_email');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['must_change_password','is_active','phone','work_email','photo_url']);
        });
    }
};
