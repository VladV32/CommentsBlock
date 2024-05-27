<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Annotations as OA;

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
