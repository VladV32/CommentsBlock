<?php

namespace App\DTO\Comment;

use App\DTO\Abstract\BaseDto;
use App\DTO\Comment\Interfaces\StoreCommentDtoInterface;
use Illuminate\Http\UploadedFile;

readonly class StoreCommentDto extends BaseDto implements StoreCommentDtoInterface
{
    /**
     * @param string            $user_name
     * @param string            $email
     * @param string            $text
     * @param null|string       $home_page
     * @param null|int          $parent_id
     * @param null|UploadedFile $avatar
     * @param null|UploadedFile $attach
     */
    public function __construct(
        protected string $user_name,
        protected string $email,
        protected string $text,
        protected ?string $home_page = null,
        protected ?int $parent_id = null,
        protected ?UploadedFile $avatar = null,
        protected ?UploadedFile $attach = null,
    ) {
        //
    }
    /**
     * @inheritDoc
     */
    public function getHomePage(): ?string
    {
        return $this->home_page;
    }

    /**
     * @inheritDoc
     */
    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    /**
     * @inheritDoc
     */
    public function getAvatar(): ?UploadedFile
    {
        return $this->avatar;
    }

    /**
     * @inheritDoc
     */
    public function getAttach(): ?UploadedFile
    {
        return $this->attach;
    }

    /**
     * @inheritDoc
     */
    public function getUserName(): string
    {
        return $this->user_name;
    }

    /**
     * @inheritDoc
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function getText(): string
    {
        return $this->text;
    }
}
