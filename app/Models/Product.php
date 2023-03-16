<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int|mixed $discount
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'discount',
        'is_active',
        'app_store_product_id',
        'google_play_product_id',
        'is_show',
        'donate_price',
    ];
}
