<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('products', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('amount')->default(0);
            $table->integer('discount')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('app_store_product_id')->nullable();
            $table->string('google_play_product_id')->nullable();
            $table->boolean('is_show')->default(false);
            $table->float('donate_price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
