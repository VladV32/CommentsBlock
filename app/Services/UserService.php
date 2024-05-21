<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;

class UserService
{
    public function __construct(protected UserRepositoryInterface $userRepository)
    {
        //
    }

    public function firstOrCreate(array $userAttributes): User
    {
        $email = $userAttributes['email'];

        $userValues = [
            'user_name' => $userAttributes['user_name'],
            'home_page' => $userAttributes['home_page'] ?? null,
            'avatar' => $userAttributes['avatar'] ?? null,
        ];

        return $this->userRepository->firstOrCreateForEmail($email, $userValues);
    }
}