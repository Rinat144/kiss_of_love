<?php

namespace App\Models;

use App\Services\Game\Enum\StatusGameEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int|mixed|string|null $fourth_user_id
 * @property false|mixed|string $fourth_user_info
 * @property int|mixed $status
 * @property int|mixed|string|null $first_user_id
 * @property false|mixed|string $first_user_info
 * @property mixed $fifth_user_id
 * @property mixed $sixth_user_id
 * @property mixed $second_user_id
 * @property mixed $third_user_id
 */
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
        'status' => StatusGameEnum::class,
        'first_user_info' => 'array',
        'second_user_info' => 'array',
        'third_user_info' => 'array',
        'fourth_user_info' => 'array',
        'fifth_user_info' => 'array',
        'sixth_user_info' => 'array',
    ];
}
