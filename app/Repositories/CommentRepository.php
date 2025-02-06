<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Repositories\Interface\CommentRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class CommentRepository extends BaseCacheRepository implements CommentRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function all(string $sortField, int $perPage, int $currentPage = 1): LengthAwarePaginator
    {
        $this->generateCacheKey('comments', [$sortField, $perPage, $currentPage]);

        return Cache::remember(
            $this->getCacheKey(),
            $this->getTTL(),
            fn () => Comment::with(['user', 'replies.user'])
                ->whereNull('parent_id')
                ->orderByDesc($sortField)
                ->paginate($perPage)
        );
    }

    /**
     * @inheritDoc
     */
    public function create(array $commentAttributes): Comment
    {
        $comment = Comment::create($commentAttributes);

        $this->generateCacheKey('comment', [$comment->id]);

        $this->deleteCacheByPrefixOrAll('comments');

        return Cache::remember(
            $this->getCacheKey(),
            $this->getTTL(),
            fn () => $comment
        );
    }

    /**
     * @inheritDoc
     */
    public function find(int $commentId): ?Comment
    {
        $this->generateCacheKey('comment', [$commentId]);

        return Cache::get($this->getCacheKey())
            ?? Cache::remember(
                $this->getCacheKey(),
                $this->getTTL(),
                fn () => Comment::find($commentId)
            );
    }

    /**
     * @inheritDoc
     */
    public function update(int $commentId, array $commentAttributes): Comment|false
    {
        if ($comment = $this->find($commentId)) {
            $comment->update($commentAttributes);

            Cache::forget($this->getCacheKey());

            Cache::put($this->getCacheKey(), $comment, $this->getTTL());

            return $comment;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function delete(int $commentId): bool
    {
        if ($comment = $this->find($commentId)) {
            Cache::forget($this->getCacheKey());

            return $comment->delete();
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function addAttachment(int $commentId, string $path): Comment|false
    {
        if ($comment = $this->find($commentId)) {
            $comment->attachments()->create(['path' => $path]);

            Cache::forget($this->getCacheKey());

            Cache::put($this->getCacheKey(), $comment, $this->getTTL());

            return $comment;
        }

        return false;
    }
}
