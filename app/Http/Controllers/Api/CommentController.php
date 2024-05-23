<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DestroyCommentRequest;
use App\Http\Requests\Api\StoreCommentRequest;
use App\Http\Requests\Api\UpdateCommentRequest;
use App\Http\Requests\Api\IndexCommentRequest;
use App\Http\Resources\Api\IndexCommentResourceCollection;
use App\Http\Resources\CommentResource;
use App\Services\CommentService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    public function index(IndexCommentRequest $request, CommentService $commentService): JsonResponse
    {
        $sortField = $request->validated('sort', 'created_at');
        $page = $request->validated('page', 1);

        $comments = $commentService->getAllComments($sortField, self::DEFAULT_PAGINATE_PER_PAGE, $page);

        return response()->json(IndexCommentResourceCollection::make($comments));
    }

    public function store(StoreCommentRequest $request, CommentService $commentService, UserService $userService): JsonResponse
    {
        $user = $userService->firstOrCreate($request, $request->validated());

        $comment = $commentService->createComment($request , $user, $request->validated());

        return response()->json(CommentResource::make($comment), 201);
    }

    public function update(UpdateCommentRequest $request, CommentService $commentService): JsonResponse
    {
        $comment = $commentService->updateComment($request->validated('comment'), $request->validated());

        return response()->json(CommentResource::make($comment));
    }

    public function destroy(DestroyCommentRequest $request, CommentService $commentService): JsonResponse
    {
        $commentService->deleteComment($request->validated('comment'));

        return response()->json(null, 204);
    }
}

