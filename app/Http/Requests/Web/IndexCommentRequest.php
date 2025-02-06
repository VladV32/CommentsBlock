<?php

namespace App\Http\Requests\Web;

use App\Enums\CommentDtoEnum;
use App\Http\Requests\BaseDtoRequests;

class IndexCommentRequest extends BaseDtoRequests
{
    /**
     * @var CommentDtoEnum
     */
    protected CommentDtoEnum $dtoClass = CommentDtoEnum::INDEX_COMMENT;

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
            'sort' => 'nullable|in:created_at,updated_at,name,email',
            'page' => 'nullable|int',
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'sort' => $this->get('sort', 'created_at'),
        ]);
    }
}
