<?php

namespace App\Http\Requests\Api;

use App\Rules\ValidHtmlRule;
use App\Services\UserService;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $maxAvatarImageWidth = UserService::AVATAR_IMAGE_MAX_WIDTH;
        $maxAvatarImageHeight = UserService::AVATAR_IMAGE_MAX_HEIGHT;
        $maxAttachImageWidth = 320;
        $maxAttachImageHeight = 240;
        $maxImageSize = 2048; // 2KB
        $maxTextFileSize = 102400; // 100KB

        return [
            'user_name' => 'required|max:150|regex:/^[a-zA-Z0-9 ]+$/',
            'email' => 'required|email|max:150',
            'home_page' => 'nullable|url|max:150',
            'avatar' => ['nullable',
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:'.$maxImageSize,
                'dimensions:min_width='.$maxAvatarImageWidth.',min_height='.$maxAvatarImageHeight
            ],
            'text' => ['required', 'string', 'max:1000', new ValidHtmlRule()],
            'attach' => [
                'nullable',
                'file',
                'mimes:jpeg,png,jpg,gif,txt',
                function (string $attribute, mixed $value, Closure $fail) use (
                    $maxAttachImageWidth,
                    $maxAttachImageHeight,
                    $maxTextFileSize,
                    $maxImageSize
                ) {
                    if (in_array($value->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'])) {
                        if ($value->getSize() > $maxImageSize) {
                            $fail('The image size should not exceed 2MB.');
                        }

                        $image = getimagesize($value->getPathname());
                        if ($image[0] > $maxAttachImageWidth || $image[1] > $maxAttachImageHeight) {
                            $fail(
                                'The image dimensions should not exceed '.$maxAttachImageWidth.'x'.$maxAttachImageHeight.' pixels.'
                            );
                        }
                    } elseif ($value->getMimeType() === 'text/plain') {
                        if ($value->getSize() > $maxTextFileSize) {
                            $fail('The text file size should not exceed 100KB.');
                        }
                    } else {
                        $fail('Invalid file type.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'attach.max' => 'The file size should not exceed 100KB.',
        ];
    }

}
