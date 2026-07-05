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
        // 1. Add nullable variant_id and nullable unit_price
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('variant_id')->nullable()->after('product_id')->constrained('variants')->restrictOnDelete();
            $table->decimal('unit_price', 10, 2)->nullable()->after('quantity');
        });

        // 2. Backfill unit_price from current products.price for existing rows
        \Illuminate\Support\Facades\DB::table('cart_items')->update([
            'unit_price' => \Illuminate\Support\Facades\DB::raw('(SELECT price FROM products WHERE products.id = cart_items.product_id)')
        ]);

        // 3. Make unit_price NOT NULL and change unique key
        Schema::table('cart_items', function (Blueprint $table) {
            $table->decimal('unit_price', 10, 2)->nullable(false)->change();
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->unique(['user_id', 'product_id', 'variant_id']);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->unique(['user_id', 'product_id']);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'product_id', 'variant_id']);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropColumn(['variant_id', 'unit_price']);
        });
    }
};
