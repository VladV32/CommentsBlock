<?php

namespace App\Rules;

use App\Services\CommentService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidHtmlRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }

    public function passes($attribute, $value): bool
    {
        // List of allowed tags
        $allowedTags = CommentService::HTML_ALLOWED_TAGS;

        // Strip the tags, leaving only the allowed ones
        $stripped = strip_tags($value, $allowedTags);

        // Check if the input text and stripped text are the same
        return $value === $stripped;
    }

    public function message(): string
    {
        return 'The :attribute field contains invalid HTML.';
    }
}
