<?php

namespace App\DTO\Abstract;

use App\Contracts\DtoInterface;

abstract readonly class BaseDto implements DtoInterface
{
    /**
     * @return DtoInterface
     */
    public function getDto(): DtoInterface
    {
        return $this;
    }
}
