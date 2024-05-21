<?php

namespace App\Repositories;

use App\Models\Comment;
use \Illuminate\Pagination\LengthAwarePaginator;

class CommentRepository implements CommentRepositoryInterface
{

    public function all(string $sortField, int $perPage): LengthAwarePaginator
    {
        return Comment::with(['user', 'replies.user'])
            ->whereNull('parent_id')
            ->orderByDesc($sortField)
            ->paginate($perPage);
    }

    public function create(array $commentAttributes): Comment
    {
        return Comment::create($commentAttributes);
    }

    public function find(int $commentId): ?Comment
    {
        return Comment::find($commentId);
    }

    public function update(int $commentId, array $commentAttributes): Comment|bool
    {
        if ($comment = $this->find($commentId)) {
            $comment->update($commentAttributes);
            return $comment;
        }
        return false;
    }

    public function delete(int $commentId): bool
    {
        if ($comment = $this->find($commentId)) {
            return $comment->delete();
        }
        return false;
    }
}