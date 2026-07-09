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
            $table->string('image')->nullable();
            $table->boolean('is_available')->default(true);
            $table->decimal('price', 10, 2);
            $table->boolean('has_discount')->default(false);
            $table->enum('category', ['men', 'women', 'kids', 'accessories', 'other'])->default('other');
            $table->enum('type', [
                'shirts',
                'pants',
                'dresses',
                'shoes',
                'jackets',
                'bags',
                'watches',
                'accessories',
                'other'
            ])->default('other');
            $table->timestamps();
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
