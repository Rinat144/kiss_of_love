<?php

namespace App\Services\User;

use App\Services\User\Enum\ExceptionEnum;
use App\Services\User\Exceptions\UserApiException;
use App\Services\User\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class UserService
{
    /**
     * @param UserRepository $userRepository
     */
    public function __construct(
        public UserRepository $userRepository,
    ) {
    }

    /**
     * @param int $sum
     * @return int
     * @throws UserApiException
     */
    final public function getCheckBalance(int $sum): int
    {
        $userBalance = Auth::user()->balance;

        if ($userBalance < $sum) {
            throw new UserApiException(ExceptionEnum::NOT_ENOUGH_MONEY);
        }

        return $userBalance;
    }

    /**
     * @param int $userId
     * @param int $userBalance
     * @param int $amountProduct
     * @return void
     */
    final public function userDeductBalance(int $userId, int $userBalance, int $amountProduct): void
    {
        $newBalance = $userBalance - $amountProduct;
        $this->userRepository->updateUserBalance($userId, $newBalance);
    }

    /**
     * @param int $userId
     * @param int $userBalance
     * @param int $amountProduct
     * @return void
     */
    final public function userAddBalance(int $userId, int $userBalance, int $amountProduct): void
    {
        $newBalance = $userBalance + $amountProduct;
        $this->userRepository->updateUserBalance($userId, $newBalance);
    }
}
