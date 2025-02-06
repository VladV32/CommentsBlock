<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\User;
use App\Repositories\CommentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CommentService
{
    public const string HTML_ALLOWED_TAGS = '<a><code><i><strong>';

    public function __construct(
        protected CommentRepositoryInterface $commentRepository,
        protected UserService $userService,
        private readonly AttachmentService $attachmentService
    ) {
        //
    }

    public function getAllComments(string $sortField, int $perPage, int $currentPage = 1): LengthAwarePaginator
    {
        return $this->commentRepository->all($sortField, $perPage, $currentPage);
    }

    public function createComment(Request $request, User $user, array $commentValues): Comment
    {
        $commentData = [
            'user_id'   => $user->id,
            'text'      => $this->getTextWithAllowedTags($commentValues['text']),
            'parent_id' => $commentValues['parent_id'] ?? null,
        ];

        $comment = $this->commentRepository->create($commentData);

        if ($path = $this->attachmentService->getPathOfAttachment($request)) {
            return $this->commentRepository->addAttachment($comment->id, $path);
        }

        return $comment;
    }

    public function updateComment(int $commentId, array $commentValues): Comment
    {
        $commentData = [
            'text' => $this->getTextWithAllowedTags($commentValues['text']),
        ];

        return $this->commentRepository->update($commentId, $commentData);
    }

    public function deleteComment(int $commentId): bool
    {
        return $this->commentRepository->delete($commentId);
    }

    private function getTextWithAllowedTags(string $text): string
    {
        return htmlspecialchars(strip_tags($text, self::HTML_ALLOWED_TAGS));
    }
}
