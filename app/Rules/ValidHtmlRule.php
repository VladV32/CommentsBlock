<?php

namespace App\Rules;

use App\Services\CommentService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidHtmlRule implements ValidationRule
{
    /**
     * @param string  $attribute
     * @param mixed   $value
     * @param Closure $fail
     *
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }

    /**
     * @param array  $attribute
     * @param string $value
     *
     * @return bool
     */
    public function passes(array $attribute, string $value): bool
    {
        // List of allowed tags
        $allowedTags = CommentService::HTML_ALLOWED_TAGS;

        // Strip the tags, leaving only the allowed ones
        $stripped = strip_tags($value, $allowedTags);

        // Check if the input text and stripped text are the same
        return $value === $stripped;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute field contains invalid HTML.';
    }
}
