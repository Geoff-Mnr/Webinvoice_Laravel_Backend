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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('documenttype_id')->constrained();
            $table->string('reference_number')->unique();
            $table->dateTime('document_date');
            $table->dateTime('due_date');
            $table->integer('price_htva');
            $table->integer('price_vvac');
            $table->integer('price_total');
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
        Schema::dropIfExists('documents');
    }
};












