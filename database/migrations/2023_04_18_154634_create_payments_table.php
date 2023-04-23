<?php

use App\Services\Payment\Enum\PaymentStatusEnum;
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
    public function up(): void
    {
        Schema::create('payments', static function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('xsolla_product_id');
            $table->float('amount');
            $table->enum('status', array_column(PaymentStatusEnum::cases(), 'value'));
            $table->integer('external_id');
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
        Schema::dropIfExists('payments');
    }
};
