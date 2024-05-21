<?php

namespace App\Http\Requests\Api;

use App\Rules\ValidHtmlRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_name' => 'required|regex:/^[a-zA-Z0-9 ]+$/',
            'email' => 'required|email',
            'home_page' => 'nullable|url',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'text' => ['required', 'string', new ValidHtmlRule()],
        ];
    }
}
