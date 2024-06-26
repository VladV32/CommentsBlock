<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class BaseCacheRepository
{
    const string CACHE_KEY_SEPARATOR = '_';

    const int ONE_HOUR_TTL = 3600;
    const int HALF_DAY_TTL = 43200;
    const int ONE_DAY_TTL = 86400;
    const int ONE_WEEK_TTL = 604800;

    private string $cacheKey;

    public function generateCacheKey(string $tag, array $data = []): void
    {
        $hashedData = array_map(fn($item) => hash('sha256', $item), $data);
        $this->setCacheKey($tag.self::CACHE_KEY_SEPARATOR.implode(self::CACHE_KEY_SEPARATOR, $hashedData));
    }

    public function getCacheKey(): string
    {
        return $this->cacheKey;
    }

    private function setCacheKey(string $cacheKey): void
    {
        $this->cacheKey = $cacheKey;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function deleteCacheByPrefixOrAll(string $prefix): void
    {
        $store = Cache::getStore();
        if (method_exists($store, 'connection')) {
            $connection = $store->connection();
            $keys = $connection->keys(Cache::getPrefix().$prefix.'*');
            Cache::deleteMultiple($keys);
        } else {
            Cache::clear();
        }
    }

    public function getTTL(int $ttl = self::ONE_HOUR_TTL): Carbon
    {
        return now()->addSeconds($ttl);
    }
}
