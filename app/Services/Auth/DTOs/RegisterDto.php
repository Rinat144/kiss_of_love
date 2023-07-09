<?php

namespace App\Services\Auth\DTOs;

use App\Services\Auth\Enum\SocialTypeEnum;

class RegisterDto
{
    /**
     * @param string $name
     * @param string $date_of_birth
     * @param string $gender
     * @param string $login
     * @param string $socialTypeEnum
     * @param string $password
     * @param int $city_id
     */
    public function __construct(
        public string $name,
        public string $date_of_birth,
        public string $gender,
        public string $login,
        public string $socialTypeEnum,
        public string $password,
        public int $city_id,
    )
    {
    }
}
