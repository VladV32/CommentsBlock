<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     schema="IndexCommentResourceCollection",
 *     @OA\Property(
 *         property="comments",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/CommentResourceCollection")
 *     ),
 *     @OA\Property(
 *         property="links",
 *         type="object",
 *         @OA\Property(property="first", type="string", format="url", description="URL to first page"),
 *         @OA\Property(property="last", type="string", format="url", description="URL to last page"),
 *         @OA\Property(property="prev", type="string", format="url", description="URL to previous page"),
 *         @OA\Property(property="next", type="string", format="url", description="URL to next page")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(property="current_page", type="integer", description="Current page number"),
 *         @OA\Property(property="from", type="integer", description="First item number on current page"),
 *         @OA\Property(property="last_page", type="integer", description="Last page number"),
 *         @OA\Property(property="path", type="string", format="url", description="CommentDTOs path"),
 *         @OA\Property(property="per_page", type="integer", description="Number of items per page"),
 *         @OA\Property(property="to", type="integer", description="Last item number on current page"),
 *         @OA\Property(property="total", type="integer", description="Total number of items")
 *     )
 * )
 */
class IndexCommentResourceCollection extends ResourceCollection
{
    /**
     * @param null|Request $request
     *
     * @return array
     */
    public function toArray(Request $request = null): array
    {
        return [
            'comments' => CommentResource::collection($this->resource),
            'links'    => [
                'first' => $this->resource->url(1),
                'last'  => $this->resource->url($this->resource->lastPage()),
                'prev'  => $this->resource->previousPageUrl(),
                'next'  => $this->resource->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $this->resource->currentPage(),
                'from'         => $this->resource->firstItem(),
                'last_page'    => $this->resource->lastPage(),
                'path'         => $this->resource->path(),
                'per_page'     => $this->resource->perPage(),
                'to'           => $this->resource->lastItem(),
                'total'        => $this->resource->total(),
            ],
        ];
    }
}
