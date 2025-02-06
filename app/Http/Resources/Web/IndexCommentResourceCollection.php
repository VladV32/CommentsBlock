<?php

namespace App\Http\Resources\Web;

use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexCommentResourceCollection extends ResourceCollection
{
    /**
     * @param LengthAwarePaginator $resource
     */
    public function __construct(LengthAwarePaginator $resource)
    {
        parent::__construct($resource);
    }

    /**
     * @param null|Request $request
     *
     * @return array
     */
    public function toArray(Request $request = null): array
    {
        return [
            'initialComments' => CommentResource::collection($this->resource),
        ];
    }
}
