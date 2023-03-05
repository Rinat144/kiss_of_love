<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int|mixed $user_id
 * @property mixed|string $image
 */
class UserAvatar extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'image',
    ];
}
