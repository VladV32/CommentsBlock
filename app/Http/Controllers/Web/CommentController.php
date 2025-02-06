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
    public function index(IndexCommentRequest $request, CommentService $commentService): Response
    {
        $sortField = $request->validated('sort', 'created_at');
        $page      = $request->validated('page', 1);

        $comments = $commentService->getAllComments($sortField, self::DEFAULT_PAGINATE_PER_PAGE, $page);

        return Inertia::render(
            'Comments/Index',
            IndexCommentResourceCollection::make($comments)->toArray()
        );
    }
}
