<?php

namespace App\Http\Requests\Api;

use App\Rules\ValidHtmlRule;
use App\Services\AttachmentService;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreCommentRequest",
 *     required={"user_name", "email", "text"},
 *     @OA\Property(
 *         property="parent_id",
 *         type="integer",
 *         nullable=true,
 *         description="ID of the parent comment if exists"
 *     ),
 *     @OA\Property(
 *         property="user_name",
 *         type="string",
 *         maxLength=150,
 *         description="Name of the user"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         maxLength=150,
 *         description="Email of the user"
 *     ),
 *     @OA\Property(
 *         property="home_page",
 *         type="string",
 *         format="url",
 *         nullable=true,
 *         maxLength=150,
 *         description="Homepage URL of the user"
 *     ),
 *     @OA\Property(
 *         property="avatar",
 *         type="string",
 *         format="binary",
 *         nullable=true,
 *         description="Avatar image file of the user"
 *     ),
 *     @OA\Property(
 *         property="text",
 *         type="string",
 *         maxLength=1000,
 *         description="Text content of the comment"
 *     ),
 *     @OA\Property(
 *         property="attach",
 *         type="string",
 *         format="binary",
 *         nullable=true,
 *         description="Attachment file for the comment (image or text file)"
 *     )
 * )
 */
class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $maxAttachImageWidth  = AttachmentService::ATTACHMENT_IMAGE_MAX_WIDTH;
        $maxAttachImageHeight = AttachmentService::ATTACHMENT_IMAGE_MAX_HEIGHT;
        $maxImageSize         = 2048 * 1000; // 2MB
        $maxTextFileSize      = 102400; // 100KB

        return [
            'parent_id' => 'nullable|exists:comments,id',
            'user_name' => 'required|max:150|regex:/^[a-zA-Z0-9 ]+$/',
            'email'     => 'required|email|max:150',
            'home_page' => 'nullable|url|max:150',
            'avatar'    => ['nullable',
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:' . $maxImageSize,
            ],
            'text'   => ['required', 'string', 'max:1000', new ValidHtmlRule()],
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
                                'The image dimensions should not exceed ' . $maxAttachImageWidth . 'x' . $maxAttachImageHeight . ' pixels.'
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
