<?php

namespace App\Http\Requests\Api;

use App\Enums\CommentDtoEnum;
use App\Http\Requests\BaseDtoRequests;

class DestroyCommentRequest extends BaseDtoRequests
{
    /**
     * @var CommentDtoEnum
     */
    protected CommentDtoEnum $dtoClass = CommentDtoEnum::DESTROY_COMMENT;

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'comment' => 'required|exists:comments,id',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'comment.exists' => trans('Comment not found'),
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'comment' => $this->route('comment'),
        ]);
    }
}
