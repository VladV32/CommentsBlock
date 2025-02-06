<?php

namespace App\DTO\Comment;

use App\DTO\Abstract\BaseDto;
use App\DTO\Comment\Interfaces\IndexCommentDtoInterface;

readonly class IndexCommentDto extends BaseDto implements IndexCommentDtoInterface
{
    /**
     * @param null|string $sort
     * @param null|int    $page
     */
    public function __construct(
        protected ?string $sort = 'created_at',
        protected ?int $page = 1,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getSort(): ?string
    {
        return $this->sort;
    }

    /**
     * @inheritDoc
     */
    public function getCurrentPage(): ?int
    {
        return $this->page;
    }
}
