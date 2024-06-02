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
        Schema::create('document_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity')->default(0);
            $table->integer('discount')->default(0);
            $table->integer('price_htva')->default(0);
            $table->integer('price_vvat')->default(0);
            $table->integer('price_total')->default(0);
            $table->integer('margin')->default(0);
            $table->integer('buying_price')->default(0);
            $table->integer('selling_price')->default(0);
            $table->longText('comment')->nullable();
            $table->longText('description')->nullable();
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
        Schema::dropIfExists('document_product');
    }
};
