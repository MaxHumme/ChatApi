<?php
namespace MaxHumme\ChatApi\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use MaxHumme\ChatApi\Http\Handlers\JsonResponseHandler;
use MaxHumme\ChatApi\Http\Traits\SendsResponse;

/**
 * Class Authenticate
 *
 * The authentication middleware. Checks if a user is authenticated
 * and sends a 401 Unauthorized response if not so.
 *
 * @author Lumen
 * @author Max Humme <max@humme.nl>
 */
final class Authenticate
{
    use SendsResponse;

    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    private $auth;

    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Contracts\Auth\Factory $auth
     * @param \MaxHumme\ChatApi\Http\Handlers\JsonResponseHandler $jsonResponse
     */
    public function __construct(
        Auth $auth,
        JsonResponseHandler $jsonResponse
    ) {
        $this->auth = $auth;
        $this->setResponseHandler($jsonResponse);
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($this->auth->guard($guard)->guest()) {
            return $this->response(401);
        }

        return $next($request);
    }
}
