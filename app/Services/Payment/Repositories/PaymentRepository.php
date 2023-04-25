<?php

namespace App\Services\Payment\Repositories;

use App\Models\Payment;
use App\Models\Product;
use App\Services\Payment\Enum\PaymentStatusEnum;

class PaymentRepository
{
    /**
     * @param string $orderId
     * @param Product $productData
     * @param int $authUserId
     * @return void
     */
    final public function store(string $orderId, Product $productData, int $authUserId): void
    {
        $payment = new Payment();

        $payment->user_id = $authUserId;
        $payment->product_id = $productData->id;
        $payment->amount = $productData->amount;
        $payment->status = PaymentStatusEnum::CREATED;
        $payment->external_id = $orderId;

        $payment->save();
    }

    /**
     * @param Payment $payment
     * @param PaymentStatusEnum $paymentStatusEnum
     * @return void
     */
    final public function updateStatusByPayment(Payment $payment, PaymentStatusEnum $paymentStatusEnum): void
    {
        $payment->status = $paymentStatusEnum;

        $payment->save();
    }

    /**
     * @param int $orderId
     * @return Payment|null
     */
    final public function getPayment(int $orderId): ?Payment
    {
        $payment = Payment::query()
            ->where('external_id', '=', $orderId)
            ->select('id', 'user_id', 'product_id', 'amount', 'status')
            ->first();

        if ($payment instanceof Payment) {
            return $payment;
        }

        return null;
    }
}
