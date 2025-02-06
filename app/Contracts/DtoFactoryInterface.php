<?php

namespace App\Contracts;

interface DtoFactoryInterface
{
    public static function make(string $dtoClass, array $data): DtoInterface;
}
