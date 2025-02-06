<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class AttachmentService
{
    public const int ATTACHMENT_IMAGE_MAX_WIDTH  = 320;
    public const int ATTACHMENT_IMAGE_MAX_HEIGHT = 240;

    public function __construct()
    {
        //
    }

    public function getPathOfAttachment(Request $request): string|false
    {
        if (! $request->hasFile('attach')) {
            return false;
        }

        $file = $request->file('attach');

        $image = ImageManager::imagick()->read($file);

        $image->cover(self::ATTACHMENT_IMAGE_MAX_WIDTH, self::ATTACHMENT_IMAGE_MAX_HEIGHT);

        $path = $file->hashName();

        Storage::disk('attachments')->put($path, (string)$image->encode());

        return $path;
    }
}
