<?php

namespace Zyh\MicroService\Http\Middleware;

use Closure;
use Zyh\MicroService\Routing\Router;

class PrepareController
{
    /**
     * Zyh router instance.
     *
     * @var \Zyh\MicroService\Routing\Router
     */
    protected $router;

    /**
     * Create a new prepare controller instance.
     *
     * @param \Zyh\MicroService\Routing\Router $router
     *
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Handle the request.
     *
     * @param \Zyh\MicroService\Http\Request $request
     * @param \Closure                $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // To prepare the controller all we need to do is call the current method on the router to fetch
        // the current route. This will create a new Zyh\MicroService\Routing\Route instance and prepare the
        // controller by binding it as a singleton in the container. This will result in the
        // controller only be instantiated once per request.
        $this->router->current();

        return $next($request);
    }
}
