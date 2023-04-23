<?php

namespace App\Models;

use App\Services\Transaction\Enum\TypeTurnoverEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed|string $user_id
 * @property mixed|string $product_id
 * @property mixed|string $amount_before
 * @property mixed|string $amount_after
 * @property mixed|string $amount
 * @property mixed|string $type
 * @property mixed $id
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'amount_before',
        'amount_after',
        'amount',
        'type',
    ];

    protected $casts = [
        'type' => TypeTurnoverEnum::class,
    ];
}
