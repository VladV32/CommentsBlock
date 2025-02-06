<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiJsonResponse extends JsonResponse
{
    public const int HTTP_CREATED    = Response::HTTP_CREATED;
    public const int HTTP_NO_CONTENT = Response::HTTP_NO_CONTENT;

    /**
     * @param ...$parameters
     *
     * @return static
     */
    public static function make(...$parameters): self
    {
        return new self(...$parameters);
    }
}
