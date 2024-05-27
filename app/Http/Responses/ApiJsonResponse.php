<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiJsonResponse extends JsonResponse
{
    public const int HTTP_CREATED = Response::HTTP_CREATED;
    public const int HTTP_NO_CONTENT = Response::HTTP_NO_CONTENT;

    public static function make(...$parameters): static
    {
        return new static(...$parameters);
    }
}