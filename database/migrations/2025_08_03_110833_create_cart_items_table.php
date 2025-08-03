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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // harga saat ditambahkan ke cart
            $table->json('product_attributes')->nullable(); // variasi produk yang dipilih
            $table->timestamps();
            
            $table->index(['cart_id']);
            $table->index(['product_id']);
            // Note: Unique constraint on JSON column requires generated column in MySQL
            // For now, we'll handle uniqueness at application level
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
