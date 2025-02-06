<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class UserService
{
    public const int AVATAR_IMAGE_MAX_WIDTH  = 50;
    public const int AVATAR_IMAGE_MAX_HEIGHT = 50;

    public function __construct(protected UserRepositoryInterface $userRepository)
    {
        //
    }

    public function firstOrCreate(Request $request, array $userAttributes): User
    {
        $email = $userAttributes['email'];

        $userValues = [
            'user_name' => $userAttributes['user_name'],
            'home_page' => $userAttributes['home_page'] ?? null,
        ];

        if ($avatar = $this->loadAvatarFile($request, $email)) {
            $userValues['avatar'] = $avatar;
        }

        return $this->userRepository->firstOrCreateForEmail($email, $userValues);
    }

    private function loadAvatarFile(Request $request, string $email): string|false
    {
        if (! $request->hasFile('avatar')) {
            return false;
        }

        $file = $request->file('avatar');

        $image = ImageManager::imagick()->read($file);

        $image->cover(self::AVATAR_IMAGE_MAX_WIDTH, self::AVATAR_IMAGE_MAX_HEIGHT);

        $path = $file->hashName();

        if ($avatar = $this->userRepository->getAvatar($email)) {
            Storage::disk('avatars')->delete($avatar);
            $this->setAvatarFile($path, $email);
        }

        Storage::disk('avatars')->put($path, (string)$image->encode());

        return $path;
    }

    private function setAvatarFile(string $path, string $email): void
    {
        $this->userRepository->setAvatar($path, $email);
    }
}
