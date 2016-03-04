<?php
namespace Nebo15\LumenOauth2\Providers;

use Illuminate\Support\ServiceProvider as LumenServiceProvider;
use Nebo15\LumenOauth2\Router;
use Nebo15\LumenOauth2\Storage\Mongo;
use OAuth2\Request;
use OAuth2\Server;

class ServiceProvider extends LumenServiceProvider
{
    public function register()
    {
        $this->app->make('config')->set('oauth2', require __DIR__ . '/../config/oauth2.php');
        $this->app->configure('oauth2');

        $userModel = new $this->app['config']['oauth2.userModel'];

        $this->app->singleton('Oauth.routes', function ($app) use ($userModel) {
            return new Router($userModel, $app);
        });

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