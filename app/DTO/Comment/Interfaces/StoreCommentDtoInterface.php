<?php

namespace App\DTO\Comment\Interfaces;

use Illuminate\Http\UploadedFile;

interface StoreCommentDtoInterface
{
    /**
     * @return string
     */
    public function getUserName(): string;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @return string
     */
    public function getText(): string;

    /**
     * @return null|string
     */
    public function getHomePage(): ?string;

    /**
     * @return null|int
     */
    public function getParentId(): ?int;

    /**
     * @return null|UploadedFile
     */
    public function getAvatar(): ?UploadedFile;

    /**
     * @return null|UploadedFile
     */
    public function getAttach(): ?UploadedFile;
}
