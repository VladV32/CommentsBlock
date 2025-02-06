<?php

namespace App\Services;

use App\Contracts\DtoInterface;
use App\DTO\Comment\CommentDto;
use App\DTO\Comment\IndexCommentDto;
use App\DTO\Comment\StoreCommentDto;
use App\DTO\Comment\UpdateCommentDto;
use App\Models\Comment;
use App\Models\User;
use App\Repositories\Interface\CommentRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Psr\SimpleCache\InvalidArgumentException;

class CommentService
{
    public const string HTML_ALLOWED_TAGS = '<a><code><i><strong>';

    /**
     * @param CommentRepositoryInterface $commentRepository
     * @param UserService                $userService
     * @param MediaService               $mediaService
     */
    public function __construct(
        protected CommentRepositoryInterface $commentRepository,
        protected UserService $userService,
        private readonly MediaService $mediaService,
    ) {
        //
    }

    /**
     * @param IndexCommentDto $dto
     * @param int             $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllComments(DtoInterface $dto, int $perPage): LengthAwarePaginator
    {
        return $this->commentRepository->all($dto->getSort(), $perPage, $dto->getCurrentPage());
    }

    /**
     * @param StoreCommentDto $dto
     * @param User            $user
     *
     * @throws InvalidArgumentException
     * @return Comment
     */
    public function createComment(DtoInterface $dto, User $user): Comment
    {
        $commentData = [
            'user_id'   => $user->id,
            'text'      => $this->getTextWithAllowedTags($dto->getText()),
            'parent_id' => $dto->getParentId(),
        ];

        $comment = $this->commentRepository->create($commentData);

        if ($dto->getAttach() && $path = $this->mediaService->getMediaPath($dto->getAttach())) {
            return $this->commentRepository->addAttachment($comment->id, $path);
        }

        return $comment;
    }

    /**
     * @param UpdateCommentDto $dto
     *
     * @return Comment
     */
    public function updateComment(DtoInterface $dto): Comment
    {
        $commentData = [
            'text' => $this->getTextWithAllowedTags($dto->getText()),
        ];

        return $this->commentRepository->update($dto->getId(), $commentData);
    }

    /**
     * @param CommentDto $dto
     *
     * @return bool
     */
    public function deleteComment(DtoInterface $dto): bool
    {
        return $this->commentRepository->delete($dto->getId());
    }

    /**
     * @param string $text
     *
     * @return string
     */
    private function getTextWithAllowedTags(string $text): string
    {
        return htmlspecialchars(strip_tags($text, self::HTML_ALLOWED_TAGS));
    }
}
