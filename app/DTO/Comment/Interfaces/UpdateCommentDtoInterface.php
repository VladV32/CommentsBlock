<?php

namespace App\DTO\Comment\Interfaces;

use App\DTO\Abstract\Interfaces\BaseDtoInterface;

interface UpdateCommentDtoInterface extends BaseDtoInterface
{
    /**
     * @return string
     */
    public function getText(): string;
}
