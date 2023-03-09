<?php

namespace App\Services\UserAvatar\DTOs;

use Illuminate\Http\UploadedFile;

class UserAvatarDto
{
    /**
     * @param UploadedFile $image
     */
    public function __construct(
        public UploadedFile $image,
    ) {
    }
}
