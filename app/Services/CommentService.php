<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\User;
use App\Repositories\CommentRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class CommentService
{
    public function __construct(protected CommentRepositoryInterface $commentRepository)
    {
        //
    }

    public function getAllComments(string $sortField, int $perPage): LengthAwarePaginator
    {
        return $this->commentRepository->all($sortField, $perPage);
    }

    public function createComment(User $user, array $commentValues): Comment
    {
        $commentData = [
            'user_id' => $user->id,
            'text' => strip_tags($commentValues['text'], '<b><i><u>'),
            'parent_id' => $commentValues['parent_id'] ?? null,
        ];
        return $this->commentRepository->create($commentData);
    }

    public function updateComment(int $commentId, array $commentValues): Comment
    {
        $commentData = [
            'text' => strip_tags($commentValues['text'], '<b><i><u>'),
        ];

        return $this->commentRepository->update($commentId, $commentData);
    }

    public function deleteComment(int $commentId): bool
    {
        return $this->commentRepository->delete($commentId);
    }

}
