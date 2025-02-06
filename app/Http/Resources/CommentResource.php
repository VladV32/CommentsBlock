<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="CommentResource",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the comment"
 *     ),
 *     @OA\Property(
 *         property="user",
 *         type="object",
 *         @OA\Property(
 *             property="user_id",",
 *             type="integer",
 *             description="Id of the user"
 *         ),
 *         @OA\Property(
 *             property="name",
 *             type="string",
 *             description="Name of the user"
 *         ),
 *         @OA\Property(
 *             property="avatar",
 *             type="string",
 *             format="url",
 *             description="URL of the user's avatar"
 *         ),
 *     ),
 *     @OA\Property(
 *         property="text",
 *         type="string",
 *         description="Text of the comment"
 *     ),
 *     @OA\Property(
 *         property="parent_id",
 *         type="integer",
 *         nullable=true,
 *         description="ID of the parent comment if exists"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Creation timestamp of the comment"
 *     ),
 *     @OA\Property(
 *         property="replies",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/CommentResource")
 *     ),
 *     @OA\Property(
 *         property="attachments",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/AttachmentResource")
 *     )
 * )
 */
class CommentResource extends JsonResource
{
    public function __construct(Comment $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request = null): array
    {
        return [
            'id'   => $this->resource->id,
            'user' => [
                'user_id' => $this->resource->user->id,
                'name'    => $this->resource->user->name,
                'avatar'  => $this->getAvatar(),
            ],
            'text'        => htmlspecialchars_decode($this->resource->text),
            'parent_id'   => $this->resource->parent_id,
            'created_at'  => $this->resource->created_at,
            'replies'     => CommentResourceCollection::make($this->resource->replies),
            'attachments' => AttachmentResourceCollection::make($this->resource->attachments),
        ];
    }

    private function getAvatar(): ?string
    {
        return $this->resource->user->avatar ? Storage::disk('avatars')->url($this->resource->user->avatar) : null;
    }
}
