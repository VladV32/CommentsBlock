<?php

namespace App\DTO\Comment;

use App\DTO\Abstract\BaseDto;
use App\DTO\Abstract\Interfaces\BaseDtoInterface;

readonly class CommentDto extends BaseDto implements BaseDtoInterface
{
    /**
     * @param int $comment
     */
    public function __construct(
        protected int $comment
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getId(): int
    {
        return $this->comment;
    }
}
