<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CommentResource extends JsonResource
{
    public function __construct(Comment $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request = null): array
    {
        return [
            'id' => $this->resource->id,
            'user' => [
                'name' => $this->resource->user->name,
                'avatar' => $this->getAvatar(),
            ],
            'text' => htmlspecialchars_decode($this->resource->text),
            'parent_id' => $this->resource->parent_id,
            'created_at' => $this->resource->created_at,
            'replies' => $this->resource->replies
        ];
    }

    private function getAvatar(): ?string
    {
        return $this->resource->user->avatar ? Storage::disk('avatars')->url($this->resource->user->avatar) : null;
    }
}
