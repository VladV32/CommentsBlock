<?php

namespace App\Services;

use App\Contracts\DtoInterface;
use App\DTO\Comment\StoreCommentDto;
use App\Models\User;
use App\Repositories\Interface\UserRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public const int AVATAR_IMAGE_MAX_WIDTH  = 50;
    public const int AVATAR_IMAGE_MAX_HEIGHT = 50;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param MediaService            $mediaService
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        private readonly MediaService $mediaService,
    ) {
        //
    }

    /**
     * @param StoreCommentDto $dto
     *
     * @return User
     */
    public function getUser(DtoInterface $dto): User
    {
        $email = $dto->getEmail();

        $userValues = [
            'user_name' => $dto->getUserName(),
            'home_page' => $dto->getHomePage(),
        ];

        if ($avatar = $this->loadAvatarFile($dto->getAvatar(), $email)) {
            $userValues['avatar'] = $avatar;
        }

        return $this->userRepository->firstOrCreateForEmail($email, $userValues);
    }

    /**
     * @param null|UploadedFile $file
     * @param string            $email
     *
     * @return false|string
     */
    private function loadAvatarFile(?UploadedFile $file, string $email): string|false
    {
        $path = $this->mediaService->getMediaPath($file, 'avatars', self::AVATAR_IMAGE_MAX_WIDTH, self::AVATAR_IMAGE_MAX_HEIGHT);

        if ($avatar = $this->userRepository->getAvatar($email)) {
            Storage::disk('avatars')->delete($avatar);
            $this->setAvatarFile($path, $email);
        }

        return $path;
    }

    /**
     * @param string $path
     * @param string $email
     *
     * @return void
     */
    private function setAvatarFile(string $path, string $email): void
    {
        $this->userRepository->setAvatar($path, $email);
    }
}
