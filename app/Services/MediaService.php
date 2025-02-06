<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class MediaService
{
    public const int ATTACHMENT_IMAGE_MAX_WIDTH  = 320;
    public const int ATTACHMENT_IMAGE_MAX_HEIGHT = 240;

    public function __construct()
    {
        //
    }

    /**
     * @param null|UploadedFile $file
     * @param string            $disk
     * @param int               $width
     * @param int               $height
     *
     * @return false|string
     */
    public function getMediaPath(?UploadedFile $file, string $disk = 'attachments', int $width = self::ATTACHMENT_IMAGE_MAX_WIDTH, int $height = self::ATTACHMENT_IMAGE_MAX_HEIGHT): string|false
    {
        if (! $file) {
            return false;
        }

        $image = ImageManager::imagick()->read($file);

        $image->cover($width, $height);

        $path = $file->hashName();

        $tempFile = tempnam(sys_get_temp_dir(), 'upload_') . '.jpg';
        file_put_contents($tempFile, (string)$image->encode());

        $updatedFile = new UploadedFile($tempFile, $file->getClientOriginalName(), 'image/jpeg', null, true);

        Storage::disk($disk)->put($path, file_get_contents($updatedFile->getPathname()));

        unlink($tempFile);

        return $path;
    }
}
