<?php
namespace Nebo15\LumenOauth2\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Nebo15\LumenOauth2\Models\Client;

class ClientAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        $client_id = $request->getUser();
        $client_secret = $request->getPassword();
        $client = Client::findByClientId($client_id);
        if (!$client || $client->getSecret() != $client_secret) {
            throw new AuthorizationException;
        } else {
            return $next($request);
        }
    }
}
