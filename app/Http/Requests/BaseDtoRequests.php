<?php

namespace App\Http\Requests;

use App\Contracts\DtoInterface;
use App\Enums\CommentDtoEnum;
use App\Factories\BaseDtoFactory;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseDtoRequests extends FormRequest implements DtoInterface
{
    /**
     * @var CommentDtoEnum
     */
    protected CommentDtoEnum $dtoClass;

    /**
     * Converts request data to DTO.
     *
     * @return DtoInterface
     */
    public function getDto(): DtoInterface
    {
        return BaseDtoFactory::make($this->dtoClass->getDtoClass(), $this->validated());
    }
}
