<?php
namespace Nebo15\LumenOauth2\Providers;

use Illuminate\Support\ServiceProvider as LumenServiceProvider;
use Nebo15\LumenOauth2\Middleware\Authenticate;
use Nebo15\LumenOauth2\Router;
use Nebo15\LumenOauth2\Storage\Mongo;
use OAuth2\Request;
use OAuth2\Server;

class ServiceProvider extends LumenServiceProvider
{
    public function register()
    {
        $this->app->configure('auth');
        $this->app->configure('oauth2');
        $this->app->make('config')->set('oauth2', require __DIR__ . '/../config/oauth2.php');
        $this->app->make('config')->set('auth', require __DIR__ . '/../config/auth.php');



        $config = $this->app['config'];
        $userModel = new $config['oauth2.userModel'];
        $this->app->singleton('Oauth.routes', function ($app) use ($userModel) {
            return new Router($userModel, $app);
        });

        $this->app->routeMiddleware([
            'oauth' => Authenticate::class,
        ]);

        $this->app['auth']->viaRequest('oauth', function () use ($userModel) {
            $server = new Server(new Mongo($userModel), ['allow_implicit' => true]);
            $request = Request::createFromGlobals();
            $response = $server->verifyResourceRequest($request);
            if ($response) {
                $response = $server->getAccessTokenData($request);
                return $response->user;
            }
        });
    }
}
