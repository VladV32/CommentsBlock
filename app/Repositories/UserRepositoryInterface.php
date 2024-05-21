<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function firstOrCreateForEmail(string $email, array $userValues): User;

    public function getAvatar(string $email): string;
}