<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Inventory Categories
        Schema::create('inventory_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Inventory Products Table
        Schema::create('inventory_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('name');
            $table->string('sku')->unique()->nullable();
            $table->integer('cost_price')->default(0);
            $table->integer('selling_price')->default(0);
            $table->integer('stock_quantity')->default(0);
            $table->integer('reorder_level')->default(5);
            $table->string('status')->default('AVAILABLE'); // AVAILABLE, OUT_OF_STOCK, INACTIVE
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('inventory_categories')->onDelete('set null');
        });

        // 3. Inventory Stock Adjustments Log Table
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('type'); // ADD, DEDUCT, SALE, CORRECTION
            $table->integer('quantity');
            $table->integer('stock_before');
            $table->integer('stock_after');
            $table->string('reason')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('inventory_products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_adjustments');
        Schema::dropIfExists('inventory_products');
        Schema::dropIfExists('inventory_categories');
    }
};
