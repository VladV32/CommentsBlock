<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class DestroyCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comment' => 'required|exists:comments,id',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'comment' => $this->route('comment'),
        ]);
    }

    public function messages(): array
    {
        return [
            'comment.exists' => trans('Comment not found'),
        ];
    }
}
