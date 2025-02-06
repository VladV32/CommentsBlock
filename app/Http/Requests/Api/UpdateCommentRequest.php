<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

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
class UpdateCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comment' => 'required|exists:comments,id',
            'text'    => 'required|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'comment.exists' => trans('Comment not found'),
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'comment' => $this->route('comment'),
        ]);
    }
}
