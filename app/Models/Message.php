<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int|mixed $chat_id
 * @property int|mixed $user_id
 * @property mixed|string $message
 */
class Message extends Model
{
    use HasFactory;
}
