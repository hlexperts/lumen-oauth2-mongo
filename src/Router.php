<?php
namespace Nebo15\LumenOauth2;

use Laravel\Lumen\Application;
use Nebo15\LumenOauth2\Interfaces\Oauthable;
use Nebo15\LumenOauth2\Storage\Mongo;
use OAuth2\GrantType\RefreshToken;
use OAuth2\GrantType\UserCredentials;
use OAuth2\Request;
use OAuth2\Server;

class Router
{
    private $app;
    protected $userModel;

    public function __construct(Oauthable $userModel, Application $app)
    {
        $this->userModel = $userModel;
        $this->app = $app;
    }

    public function makeRestRoutes()
    {
        $storage = new Mongo($this->userModel);
        $server = new Server($storage, ['allow_implicit' => true]);
        $request = Request::createFromGlobals();

        $this->app->post('/oauth/', function () use ($server, $storage, $request) {
            $server->addGrantType(new UserCredentials($storage));
            $server->addGrantType(new RefreshToken($storage, ['always_issue_new_refresh_token' => true]));
            $access_token = $server->handleTokenRequest($request);

            return response($access_token->getResponseBody(), $access_token->getStatusCode());
        });

        $this->app->get(
            '/test_user',
            ['uses' => '\Nebo15\LumenOauth2\Controllers\IndexController@index', 'middleware' => ['oauth']]
        );
    }
}
