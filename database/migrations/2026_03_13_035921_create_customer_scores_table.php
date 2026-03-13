<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('favorite_category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->decimal('engagement_score', 8, 2)->default(0);
            $table->decimal('purchase_score', 8, 2)->default(0);
            $table->decimal('retention_probability', 5, 2)->default(0);
            $table->string('segment')->default('cold');
            $table->text('recommended_product_ids')->nullable();
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_scores');
    }
};
