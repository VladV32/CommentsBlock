<?php

namespace App\Http\Requests\Api;

use App\Enums\CommentDtoEnum;
use App\Http\Requests\BaseDtoRequests;

/**
 * @OA\Schema(
 *     schema="UpdateCommentRequest",
 *     required={"text"},
 *     @OA\Property(
 *         property="text",
 *         type="string",
 *         maxLength=1000,
 *         description="Text content of the comment"
 *     )
 * )
 */
class UpdateCommentRequest extends BaseDtoRequests
{
    /**
     * @var CommentDtoEnum
     */
    protected CommentDtoEnum $dtoClass = CommentDtoEnum::UPDATE_COMMENT;

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
            'text'    => 'required|string|max:1000',
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
