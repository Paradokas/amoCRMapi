<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

class AmoCRMLogger
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return Response
     * @throws JsonException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        Log::channel('amocrm')->info('AmoCRM request', [
            'request' => [
                'method' => $request->getMethod(),
                'url' => $request->fullUrl(),
                'body' => $request->except(['_token', '_method']),
            ],
            'response' => [
                'code' => $response->getStatusCode(),
            ],

        ]);

        return $response;
    }
}
