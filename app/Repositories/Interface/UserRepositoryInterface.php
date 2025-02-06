<?php

namespace App\Repositories\Interface;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * @param string $email
     * @param array  $userValues
     *
     * @return User
     */
    public function firstOrCreateForEmail(string $email, array $userValues): User;

    /**
     * @param string $email
     *
     * @return string
     */
    public function getAvatar(string $email): string;

    /**
     * @param string $path
     * @param string $email
     *
     * @return void
     */
    public function setAvatar(string $path, string $email): void;
}
