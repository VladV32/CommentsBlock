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
    /**
     * @param Attachment $resource
     */
    public function __construct(Attachment $resource)
    {
        parent::__construct($resource);
    }

    /**
     * @param null|Request $request
     *
     * @return null[]|string[]
     */
    public function toArray(Request $request = null): array
    {
        return [
            'url' => $this->getAttachment(),
        ];
    }

    /**
     * @return null|string
     */
    private function getAttachment(): ?string
    {
        return $this->resource->path ? Storage::disk('attachments')->url($this->resource->path) : null;
    }
}
