<?php

namespace App\Enums;

use App\DTO\Abstract\Interfaces\BaseEnumDtoInterface;
use App\DTO\Comment\DestroyCommentDto;
use App\DTO\Comment\IndexCommentDto;
use App\DTO\Comment\StoreCommentDto;
use App\DTO\Comment\UpdateCommentDto;

enum CommentDtoEnum: string implements BaseEnumDtoInterface
{
    case INDEX_COMMENT   = 'index_comment';
    case STORE_COMMENT   = 'store_comment';
    case UPDATE_COMMENT  = 'update_comment';
    case DESTROY_COMMENT = 'destroy_comment';

    /**
     * @return string
     */
    public function getDtoClass(): string
    {
        return match ($this) {
            self::INDEX_COMMENT   => IndexCommentDto::class,
            self::STORE_COMMENT   => StoreCommentDto::class,
            self::UPDATE_COMMENT  => UpdateCommentDto::class,
            self::DESTROY_COMMENT => DestroyCommentDto::class,
        };
    }
}
