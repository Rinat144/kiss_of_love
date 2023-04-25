<?php

namespace App\Services\Transaction\Repositories;

use App\Models\Transaction;
use App\Services\Transaction\Enum\TypeTurnoverEnum;

class TransactionRepository
{
    /**
     * @param int $userId
     * @param int $productId
     * @param int $infoBalance
     * @param int $amountProduct
     * @return void
     */
    final public function storeOutlayTransaction(
        int $userId,
        int $productId,
        int $infoBalance,
        int $amountProduct
    ): void {
        $transaction = new Transaction();

        $transaction->user_id = $userId;
        $transaction->product_id = $productId;
        $transaction->amount_before = $infoBalance;
        $transaction->amount_after = $infoBalance - $amountProduct;
        $transaction->amount = $amountProduct;
        $transaction->type = TypeTurnoverEnum::OUTLAY;

        $transaction->save();
    }

    /**
     * @param int $userId
     * @param int $productId
     * @param int $infoBalance
     * @param int $amountProduct
     * @return void
     */
    final public function storeInflowTransaction(
        int $userId,
        int $productId,
        int $infoBalance,
        int $amountProduct
    ): void {
        $transaction = new Transaction();

        $transaction->user_id = $userId;
        $transaction->product_id = $productId;
        $transaction->amount_before = $infoBalance;
        $transaction->amount_after = $infoBalance + $amountProduct;
        $transaction->amount = $amountProduct;
        $transaction->type = TypeTurnoverEnum::INFLOW;

        $transaction->save();
    }
}
