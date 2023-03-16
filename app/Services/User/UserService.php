<?php

namespace App\Services\User;

use App\Services\User\Enum\StatusBalanceEnum;
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
    final public function getValueBalance(int $sum): int
    {
        $userId = Auth::id();
        $infoBalance = $this->userRepository->getValueBalance($userId, $sum);

        if (!$infoBalance) {
            throw new UserApiException(StatusBalanceEnum::NOT_ENOUGH_MONEY);
        }

        return $infoBalance;
    }

    /**
     * @param int $userId
     * @param int $infoBalance
     * @param int $amountProduct
     * @return void
     */
    final public function updateUserBalance(int $userId, int $infoBalance, int $amountProduct): void
    {
        $newBalance = $infoBalance - $amountProduct;
        $this->userRepository->updateUserBalance($userId, $newBalance);
    }
}
