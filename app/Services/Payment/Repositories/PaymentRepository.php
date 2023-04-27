<?php

namespace App\Services\Payment\Repositories;

use App\Models\Payment;
use App\Models\Product;
use App\Services\Payment\Enum\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Builder;

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
    final public function getPaymentByOrderId(int $orderId): ?Payment
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

    /**
     * @param int $authUserId
     * @return Builder
     */
    final public function getBuilderPurchasedProducts(int $authUserId): Builder
    {
        return Payment::query()
            ->where('user_id', '=', $authUserId)
            ->where('status', '=', PaymentStatusEnum::PAID_FOR)
            ->groupBy('payments.id', 'payments.user_id');
    }

    /**
     * @param Builder $builder
     * @return void
     */
    final public function updateStatusPurchasedProducts(Builder $builder): void
    {
        $builder->update(['status' => PaymentStatusEnum::RECEIVED]);
    }
}
