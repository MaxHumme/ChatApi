<?php
namespace MaxHumme\ChatApi\Framework\Providers;

use Illuminate\Support\ServiceProvider;
use MaxHumme\ChatApi\Infrastructure\Orm\User;

/**
 * Class AuthServiceProvider
 *
 * @author Lumen
 * @author Max Humme <max@humme.nl>
 */
final class AuthServiceProvider extends ServiceProvider
{
    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            if (!$request->hasHeader('Authorization')) {
                return null;
            }
            return User::where('auth_token', $request->header('Authorization'))->first();
        });
    }
}
