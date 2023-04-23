<?php

namespace App\Services\Payment\Repositories;

use App\Models\Payment;
use App\Models\Product;
use App\Services\Payment\Enum\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Model;

class PaymentRepository
{
    /**
     * @param string $orderId
     * @param Product $productData
     * @param int $authUserId
     * @return void
     */
    final public function store(string $orderId, Model $productData, int $authUserId): void
    {
        $payment = new Payment();

        $payment->user_id = $authUserId;
        $payment->xsolla_product_id = $productData->id;
        $payment->amount = $productData->amount;
        $payment->status = PaymentStatusEnum::CREATED->value;
        $payment->external_id = $orderId;

        $payment->save();
    }

    /**
     * @param int $orderId
     * @return void
     */
    final public function update(int $orderId): void
    {
        Payment::query()
            ->where('external_id', '=', $orderId)
            ->update(['status' => PaymentStatusEnum::PAID_FOR->value]);
    }
}
