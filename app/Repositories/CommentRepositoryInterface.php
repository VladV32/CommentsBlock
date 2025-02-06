<?php

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Pagination\LengthAwarePaginator;

interface CommentRepositoryInterface
{
    public function all(string $sortField, int $perPage, int $currentPage = 1): LengthAwarePaginator;

    public function create(array $commentAttributes): Comment;

    public function find(int $commentId): ?Comment;

    public function update(int $commentId, array $commentAttributes): Comment|false;

    public function delete(int $commentId): bool;

    public function addAttachment(int $commentId, string $path): Comment|false;
}
