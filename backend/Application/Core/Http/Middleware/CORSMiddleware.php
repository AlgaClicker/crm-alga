<?php

namespace Core\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CORSMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // TODO: Should check whether route has been registered

        if ($this->isPreflightRequest($request)) {
            $response = $this->createEmptyResponse();
        } else {
            try {
                $response = $next($request);
            } catch (\Exception $e) {
                $response = new JsonResponse([]);
            }
        }

        return $this->addCorsHeaders($request, $response);
    }

    /**
     * Determine if request is a preflight request.
     *
     * @param Request $request
     *
     * @return bool
     */
    protected function isPreflightRequest(Request $request)
    {
        return $request->isMethod('OPTIONS');
    }

    /**
     * Create empty response for preflight request.
     *
     * @return Response
     */
    protected function createEmptyResponse()
    {
        return new Response(null, 204);
    }

    /**
     * Add CORS headers.
     *
     * @param Request $request
     * @param Response|JsonResponse $response
     * @return Response
     */
    protected function addCorsHeaders(Request $request, $response)
    {

        $headers = [
            'Access-Control-Allow-Origin' => ['*'],
            'Access-Control-Max-Age' => (60 * 60 * 24),
            'Access-Control-Allow-Headers' => $request->header('Access-Control-Request-Headers'),
            'Access-Control-Allow-Methods' => $request->header('Access-Control-Request-Methods')
                ?: 'GET, HEAD, POST, PUT, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Credentials' => 'true',
        ];

        foreach ($headers as $header => $value) {
                $response->header($header, $value);
        }

        return $response;
    }
}
