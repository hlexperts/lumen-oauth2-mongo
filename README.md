# Lumen oauth2 mongo

Package for creating oauthable authorization in project.

## TODO

- Model User should be implement Nebo15\LumenOauth2\Interfaces\Oauthable
- Add trait for model User Nebo15\LumenOauth2\Traits\Oauthable
- Add service provider Nebo15\LumenOauth2\Providers\ServiceProvider
- Add routeMiddleware Nebo15\LumenOauth2\Middleware\Authenticate
- In routes.php call routes $app->make('Oauth.routes')->makeRestRoutes();
- Add auth.php config

```php
<?php
return [
    'defaults' => [
        'guard' => 'oauth',
    ],
    'guards' => [
        'oauth' => ['driver' => 'oauth'],
    ],
];
```