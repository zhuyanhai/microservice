<?php

namespace Zyh\MicroService\Http\Middleware;

use Closure;
use Zyh\MicroService\Routing\Router;
use Zyh\MicroService\Auth\Auth as Authentication;

class Auth
{
    /**
     * Router instance.
     *
     * @var \Zyh\MicroService\Routing\Router
     */
    protected $router;

    /**
     * Authenticator instance.
     *
     * @var \Zyh\MicroService\Auth\Auth
     */
    protected $auth;

    /**
     * Create a new auth middleware instance.
     *
     * @param \Zyh\MicroService\Routing\Router $router
     * @param \Zyh\MicroService\Auth\Auth      $auth
     *
     * @return void
     */
    public function __construct(Router $router, Authentication $auth)
    {
        $this->router = $router;
        $this->auth = $auth;
    }

    /**
     * Perform authentication before a request is executed.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $route = $this->router->getCurrentRoute();

        if (! $this->auth->check(false)) {
            $this->auth->authenticate($route->getAuthenticationProviders());
        }

        return $next($request);
    }
}
