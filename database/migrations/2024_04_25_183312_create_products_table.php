<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('brand');
            $table->string('ean_code')->unique();
            $table->string('stock')->default(0);
            $table->string('buying_price');
            $table->string('selling_price');
            $table->string('discount')->default(0);
            $table->longText('description')->nullable();
            $table->longText('comment')->nullable();
            $table->string('status', 1)->default('N');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('created_by')->nullable(false)->default(1);
            $table->unsignedInteger('updated_by')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
