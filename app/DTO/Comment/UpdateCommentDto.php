<?php

namespace App\DTO\Comment;

use App\DTO\Comment\Interfaces\UpdateCommentDtoInterface;

readonly class UpdateCommentDto extends CommentDto implements UpdateCommentDtoInterface
{
    /**
     * @param int    $comment
     * @param string $text
     */
    public function __construct(
        int $comment,
        protected string $text
    ) {
        parent::__construct($comment);
    }

    /**
     * @inheritDoc
     */
    public function getText(): string
    {
        return $this->text;
    }
}
