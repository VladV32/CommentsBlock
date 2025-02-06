<?php

namespace App\Factories;

use App\Contracts\DtoFactoryInterface;
use App\Contracts\DtoInterface;

class BaseDtoFactory implements DtoFactoryInterface
{
    /**
     * @param string $dtoClass
     * @param array  $data
     *
     * @return DtoInterface
     */
    public static function make(string $dtoClass, array $data): DtoInterface
    {
        return new $dtoClass(...$data);
    }
}
