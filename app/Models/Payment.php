<?php

namespace App\Models;

use App\Services\Payment\Enum\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed|string $user_id
 * @property mixed|string $xsolla_product_id
 * @property mixed|string $amount
 * @property mixed|string $status
 * @property mixed|string $external_id
 * @property mixed $id
 */
class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'xsolla_product_id',
        'amount',
        'status',
        'external_id',
    ];

    protected $casts = [
        'status' => PaymentStatusEnum::class,
    ];
}
