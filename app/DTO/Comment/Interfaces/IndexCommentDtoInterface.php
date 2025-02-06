<?php

namespace App\DTO\Comment\Interfaces;

interface IndexCommentDtoInterface
{
    /**
     * @return null|string
     */
    public function getSort(): ?string;

    /**
     * @return null|int
     */
    public function getCurrentPage(): ?int;
}
