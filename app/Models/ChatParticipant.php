<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed|string $chat_id
 * @property mixed|string $user_id
 */
class ChatParticipant extends Model
{
    use HasFactory;
}
