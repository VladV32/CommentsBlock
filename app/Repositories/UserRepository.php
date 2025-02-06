<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interface\UserRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class UserRepository extends BaseCacheRepository implements UserRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function firstOrCreateForEmail(string $email, array $userValues): User
    {
        $this->generateCacheKey('user_email', [$email]);

        return Cache::remember(
            $this->getCacheKey(),
            $this->getTTL(),
            fn () => User::firstOrCreate(
                ['email' => $email],
                [
                    'name'      => $userValues['user_name'],
                    'password'  => md5(fake()->password),
                    'home_page' => $userValues['home_page'] ?? null,
                    'avatar'    => $userValues['avatar'] ?? null,
                ]
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function getAvatar(string $email): string
    {
        $this->generateCacheKey('user_avatar', [$email]);

        return Cache::remember(
            $this->getCacheKey(),
            $this->getTTL(),
            fn () => User::where('email', $email)->first('avatar') ?? ''
        );
    }

    /**
     * @inheritDoc
     */
    public function setAvatar(string $path, string $email): void
    {
        User::where('email', $email)->update(['avatar' => $path]);

        $this->getAvatar($email);

        Cache::forget($this->getCacheKey());

        Cache::put($this->getCacheKey(), $path, $this->getTTL());
    }
}
