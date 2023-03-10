<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed $id
 */
class Chat extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @return HasMany
     */
    final public function chatParticipants(): HasMany
    {
        return $this->hasMany(ChatParticipant::class);
    }
}
