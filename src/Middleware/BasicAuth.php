<?php
namespace Nebo15\LumenOauth2\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Auth\Access\UnauthorizedException;

class BasicAuth
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param Closure $next
     * @return mixed
     * @throws UnauthorizedException
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->basic('username')) {
            throw new UnauthorizedException;
        }

        return $next($request);
    }
}
