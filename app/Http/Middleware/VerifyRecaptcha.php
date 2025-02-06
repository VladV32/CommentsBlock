<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VerifyRecaptcha
{
    public function __construct(protected JsonResponse $jsonResponse)
    {
        //
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request             $request
     * @param  Closure             $next
     * @throws ConnectionException
     * @throws Exception
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $recaptchaResponse = $request->input('g-recaptcha-response');

        if (! $recaptchaResponse) {
            Log::error('reCAPTCHA response is missing.');

            return new $this->jsonResponse(
                ['error' => 'reCAPTCHA verification failed'],
                $this->jsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => env('VITE_RECAPTCHA_SECRET'),
                'response' => $recaptchaResponse,
                'remoteip' => $request->ip(),
            ])->json();

            if (! isset($response['success']) || $response['success'] !== true) {
                Log::error('reCAPTCHA verification failed', ['response' => $response]);

                return new $this->jsonResponse(
                    ['error' => 'Captcha error! try again later or contact site admin.'],
                    $this->jsonResponse::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            return $next($request);
        } catch (Exception $e) {
            Log::error('reCAPTCHA verification failed.');
            throw new Exception($e->getMessage(), $this->jsonResponse::HTTP_EXPECTATION_FAILED);
        }
    }
}
