<?php

namespace App\Models;

use App\Services\Transaction\Enum\TypeTurnoverEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
