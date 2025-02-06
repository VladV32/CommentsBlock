<?php

namespace App\Http\Resources\Web;

use App\Http\Resources\CommentResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexCommentResourceCollection extends ResourceCollection
{
    public function __construct(LengthAwarePaginator $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request = null): array
    {
        return [
            'initialComments' => CommentResourceCollection::make($this->resource),
        ];
    }
}
