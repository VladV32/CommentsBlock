<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     schema="CommentResourceCollection",
 *     type="array",
 *     @OA\Items(ref="#/components/schemas/CommentResource")
 * )
 */
class CommentResourceCollection extends ResourceCollection
{
    //
}
