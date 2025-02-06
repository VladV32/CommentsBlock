<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class IndexCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sort' => 'nullable|in:created_at,updated_at,name,email',
            'page' => 'nullable|integer',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'sort' => $this->get('sort', 'created_at'),
        ]);
    }
}
