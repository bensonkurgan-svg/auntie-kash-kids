<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('discovery_forms', function (Blueprint $table) {
            $table->foreignId('assigned_staff_id')->nullable()->after('status')
                  ->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable()->after('assigned_staff_id');
        });
    }
    public function down(): void {
        Schema::table('discovery_forms', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_staff_id');
            $table->dropColumn('assigned_at');
        });
    }
};
