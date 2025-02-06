<?php

namespace App\Http\Controllers\Api;

use App\Events\CommentCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DestroyCommentRequest;
use App\Http\Requests\Api\IndexCommentRequest;
use App\Http\Requests\Api\StoreCommentRequest;
use App\Http\Requests\Api\UpdateCommentRequest;
use App\Http\Resources\Api\IndexCommentResourceCollection;
use App\Http\Resources\CommentResource;
use App\Http\Responses\ApiJsonResponse;
use App\Services\CommentService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *      title="Comments Block",
 *      version="1.0.0",
 *      description="API documentation for managing comments",
 *      @OA\Contact(
 *          email="admin@example.com",
 *          name="Admin"
 *      ),
 *      @OA\License(
 *          name="MIT",
 *          url="https://opensource.org/licenses/MIT"
 *      )
 *  )
 *
 * @OA\Tag(
 *     name="Comments",
 *     description="API Endpoints of Comments"
 * )
 */
class CommentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/comments",
     *     tags={"Comments"},
     *     summary="Get list of comments",
     *     description="Returns list of comments",
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Field to sort by",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/IndexCommentResourceCollection")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Bad request")
     * )
     */
    public function index(IndexCommentRequest $request, CommentService $commentService): JsonResponse
    {
        $sortField = $request->validated('sort', 'created_at');
        $page      = $request->validated('page', 1);

        $comments = $commentService->getAllComments($sortField, self::DEFAULT_PAGINATE_PER_PAGE, $page);

        return ApiJsonResponse::make(IndexCommentResourceCollection::make($comments));
    }
    /**
     * @OA\Post(
     *     path="/api/comments",
     *     tags={"Comments"},
     *     summary="Create a new comment",
     *     description="Creates a new comment",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreCommentRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CommentResource")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Bad request")
     * )
     */
    public function store(StoreCommentRequest $request, CommentService $commentService, UserService $userService): JsonResponse
    {
        $user = $userService->firstOrCreate($request, $request->validated());

        $comment = $commentService->createComment($request, $user, $request->validated());

        broadcast(new CommentCreated(CommentResource::make($comment)))->toOthers();

        return ApiJsonResponse::make(CommentResource::make($comment), ApiJsonResponse::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *     path="/api/comments/{id}",
     *     tags={"Comments"},
     *     summary="Update a comment",
     *     description="Updates an existing comment",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the comment to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCommentRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CommentResource")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not found"),
     *     @OA\Response(response=422, description="Bad request")
     * )
     */
    public function update(UpdateCommentRequest $request, CommentService $commentService): JsonResponse
    {
        $comment = $commentService->updateComment($request->validated('comment'), $request->validated());

        return ApiJsonResponse::make(CommentResource::make($comment));
    }

    /**
     * @OA\Delete(
     *     path="/api/comments/{id}",
     *     tags={"Comments"},
     *     summary="Delete a comment",
     *     description="Deletes an existing comment",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the comment to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Comment deleted successfully"
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not found"),
     *     @OA\Response(response=422, description="Bad request")
     * )
     */
    public function destroy(DestroyCommentRequest $request, CommentService $commentService): JsonResponse
    {
        $commentService->deleteComment($request->validated('comment'));

        return ApiJsonResponse::make(null, ApiJsonResponse::HTTP_NO_CONTENT);
    }
}
