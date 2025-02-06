<?php

namespace App\Repositories\Interface;

use App\Models\Comment;
use Illuminate\Pagination\LengthAwarePaginator;
use Psr\SimpleCache\InvalidArgumentException;

interface CommentRepositoryInterface
{
    /**
     * @param string $sortField
     * @param int    $perPage
     * @param int    $currentPage
     *
     * @return LengthAwarePaginator
     */
    public function all(string $sortField, int $perPage, int $currentPage = 1): LengthAwarePaginator;

    /**
     * @param array $commentAttributes
     *
     * @throws InvalidArgumentException
     * @return Comment
     */
    public function create(array $commentAttributes): Comment;

    /**
     * @param int $commentId
     *
     * @return null|Comment
     */
    public function find(int $commentId): ?Comment;

    /**
     * @param int   $commentId
     * @param array $commentAttributes
     *
     * @return Comment|false
     */
    public function update(int $commentId, array $commentAttributes): Comment|false;

    /**
     * @param int $commentId
     *
     * @return bool
     */
    public function delete(int $commentId): bool;

    /**
     * @param int    $commentId
     * @param string $path
     *
     * @return Comment|false
     */
    public function addAttachment(int $commentId, string $path): Comment|false;
}
