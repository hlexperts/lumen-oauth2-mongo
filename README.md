# Lumen oauth2 mongo

Package for creating oauthable authorization in project.

## TODO

- Model User should be implement Nebo15\LumenOauth2\Interfaces\Oauthable
- Add trait for model User Nebo15\LumenOauth2\Traits\Oauthable
- Add service provider Nebo15\LumenOauth2\Providers\ServiceProvider
- In routes.php call routes $app->make('Oauth.routes')->makeRestRoutes();

Routes middleware for oauth: 'oauth'