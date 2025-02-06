<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class BaseCacheRepository
{
    public const string CACHE_KEY_SEPARATOR = '_';

    public const int ONE_HOUR_TTL = 3600;
    public const int HALF_DAY_TTL = 43200;
    public const int ONE_DAY_TTL  = 86400;
    public const int ONE_WEEK_TTL = 604800;

    private string $cacheKey;

    /**
     * @param string $tag
     * @param array  $data
     *
     * @return void
     */
    public function generateCacheKey(string $tag, array $data = []): void
    {
        $hashedData = array_map(fn ($item) => hash('sha256', $item), $data);
        $this->setCacheKey($tag . self::CACHE_KEY_SEPARATOR . implode(self::CACHE_KEY_SEPARATOR, $hashedData));
    }

    /**
     * @return string
     */
    public function getCacheKey(): string
    {
        return $this->cacheKey;
    }

    /**
     * @param string $prefix
     *
     * @throws InvalidArgumentException
     * @return void
     */
    public function deleteCacheByPrefixOrAll(string $prefix): void
    {
        $store = Cache::getStore();

        if (method_exists($store, 'connection')) {
            $connection = $store->connection();
            $keys       = $connection->keys(Cache::getPrefix() . $prefix . '*');
            Cache::deleteMultiple($keys);
        } else {
            Cache::clear();
        }
    }

    /**
     * @param int $ttl
     *
     * @return Carbon
     */
    public function getTTL(int $ttl = self::ONE_HOUR_TTL): Carbon
    {
        return now()->addSeconds($ttl);
    }

    /**
     * @param string $cacheKey
     *
     * @return void
     */
    private function setCacheKey(string $cacheKey): void
    {
        $this->cacheKey = $cacheKey;
    }
}
