<?php

namespace App\Models;

use App\Services\Game\Enum\StatusGameEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'first_user_id',
        'second_user_id',
        'third_user_id',
        'fourth_user_id',
        'fifth_user_id',
        'sixth_user_id',
        'first_user_info',
        'second_user_info',
        'third_user_info',
        'fourth_user_info',
        'fifth_user_info',
        'sixth_user_info',
        'status',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'status' => StatusGameEnum::class
    ];
}
