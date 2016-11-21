<?php
namespace MaxHumme\ChatApi\Http\Middleware;

use MaxHumme\ChatApi\Http\Handlers\JsonResponseHandler;
use MaxHumme\ChatApi\Http\Traits\SendsResponse;
use Closure;

/**
 * Class JsonRequest
 *
 * The JsonRequest middleware. Checks if the request is in JSON format.
 *
 * @author Max Humme <max@humme.nl>
 */
final class JsonRequest
{
    use SendsResponse;

    /**
     * Create a new middleware instance.
     *
     * @param \MaxHumme\ChatApi\Http\Handlers\JsonResponseHandler $jsonResponse
     */
    public function __construct(
        JsonResponseHandler $jsonResponse
    ) {
        $this->setResponseHandler($jsonResponse);
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->requestInExpectedFormat($request)) {
            return $this->response(415);
        } elseif (!$this->requestJsonIsValid($request)) {
            return $this->response(400);
        }

        return $next($request);
    }

    /**
     * Returns if the $request is in the expected format.
     *
     * @param \Illuminate\Http\Request $request
     * @return boolean
     */
    private function requestExpectsJson($request)
    {
        $method = $request->getMethod();
        return $method === 'POST' || $method === 'PUT';
    }

    /**
     * Returns if the $request is in the expected format.
     *
     * @param \Illuminate\Http\Request $request
     * @return boolean
     */
    private function requestInExpectedFormat($request)
    {
        if (!$this->requestExpectsJson($request)) {
            return true;
        }

        return $request->isJson();
    }

    /**
     * Returns if the JSON request body is valid.
     *
     * @param \Illuminate\Http\Request $request
     * @return boolean
     */
    private function requestJsonIsValid($request)
    {
        if (!$this->requestExpectsJson($request)) {
            return true;
        }

        return !is_null(json_decode($request->getContent()));
    }
}
