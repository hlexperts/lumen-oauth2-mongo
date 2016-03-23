<?php
namespace Nebo15\LumenOauth2\Providers;

use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider as LumenServiceProvider;
use Nebo15\LumenOauth2\Middleware\Authenticate;
use Nebo15\LumenOauth2\Middleware\ClientAuthenticate;
use Nebo15\LumenOauth2\Router;
use Nebo15\LumenOauth2\Storage\Mongo;
use OAuth2\Request;
use OAuth2\Server;

class ServiceProvider extends LumenServiceProvider
{

    private $configs = [
        'oauth2',
        'auth',
    ];

    public function boot()
    {
        foreach ($this->configs as $config) {
            $config = $config  . '.php';
            $this->publishes([
                __DIR__ . '/../config/' . $config => app()->basePath() . '/config' . ($config ? '/' . $config : $config),
            ]);
        }
    }

    public function register()
    {
        $this->app->configure('auth');
        $this->app->make('config')->set('auth', require __DIR__ . '/../config/auth.php');
        foreach ($this->configs as $config) {
            $this->mergeConfigFrom(
                __DIR__ . '/../config/' . $config . '.php',
                $config
            );
        }

        $config = $this->app['config'];
        $userModel = new $config['oauth2.userModel'];


        $this->app->routeMiddleware([
            'oauth' => Authenticate::class,
            'oauth.basic.client' => ClientAuthenticate::class,
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

        $this->app->singleton('oauth.routes', function ($app) use ($userModel) {
            return new Router($userModel, $app, Response::create());
        });

    }
}
