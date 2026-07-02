<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('shop_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['MERCH','BOOK','OTHER'])->default('MERCH');
            $table->decimal('price', 8, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->string('image_url')->nullable();
            $table->string('external_url')->nullable();   // Amazon link for books
            $table->boolean('is_featured')->default(false); // show on homepage
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('shop_products'); }
};
