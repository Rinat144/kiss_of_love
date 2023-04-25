<?php

namespace App\Models;

use App\Services\Payment\Enum\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property mixed|string $user_id
 * @property mixed|string $amount
 * @property mixed|string $status
 * @property mixed|string $external_id
 * @property mixed $id
 * @property mixed $product_id
 * @property mixed $user
 * @property mixed $product
 */
class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'amount',
        'status',
        'external_id',
    ];

    protected $casts = [
        'status' => PaymentStatusEnum::class,
    ];

    /**
     * @return HasOne
     */
    final public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return HasOne
     */
    final public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
