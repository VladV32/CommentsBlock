<?php

namespace App\Http\Resources;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="AttachmentResource",
 *     @OA\Property(
 *         property="url",
 *         type="string",
 *         format="url",
 *         description="URL of the attachment"
 *     )
 * )
 */
class AttachmentResource extends JsonResource
{
    public function __construct(Attachment $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request = null): array
    {
        return [
            'url' => $this->getAttachment(),
        ];
    }

    private function getAttachment(): ?string
    {
        return $this->resource->path ? Storage::disk('attachments')->url($this->resource->path) : null;
    }
}
