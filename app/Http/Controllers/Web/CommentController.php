<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\IndexCommentRequest;
use App\Http\Resources\Web\IndexCommentResourceCollection;
use App\Services\CommentService;
use Inertia\Inertia;
use Inertia\Response;

class CommentController extends Controller
{
    /**
     * @param IndexCommentRequest $request
     * @param CommentService      $commentService
     *
     * @return Response
     */
    public function index(IndexCommentRequest $request, CommentService $commentService): Response
    {
        $comments = $commentService->getAllComments($request->getDto(), self::DEFAULT_PAGINATE_PER_PAGE);

        return Inertia::render(
            'Comments/Index',
            IndexCommentResourceCollection::make($comments)->toArray()
        );
    }
}
