<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function firstOrCreateForEmail(string $email, array $userValues): User
    {
        return User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $userValues['user_name'],
                'password' => md5(fake()->password),
                'home_page' => $userValues['home_page'] ?? null,
                'avatar' => $userValues['avatar'] ?? null,
            ]
        );
    }

    public function getAvatar(string $email): string
    {
        return User::query()
            ->where('email', $email)
            ->get(['avatar']);
    }
}