<?php

namespace Zyh\MicroService\Provider;

use Zyh\MicroService\Routing\Router;
use Zyh\MicroService\Routing\UrlGenerator;
use Zyh\MicroService\Contract\Routing\Adapter;
use Zyh\MicroService\Routing\ResourceRegistrar;
use Zyh\MicroService\Contract\Debug\ExceptionHandler;

class RoutingServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerRouter();

        $this->registerUrlGenerator();
    }

    /**
     * Register the router.
     */
    protected function registerRouter()
    {
        $this->app->singleton('microservice.router', function ($app) {
            $router = new Router(
                $app[Adapter::class],
                $app[ExceptionHandler::class],
                $app,
                $this->config('domain'),
                $this->config('prefix')
            );

            $router->setConditionalRequest($this->config('conditionalRequest'));

            return $router;
        });

        $this->app->singleton(ResourceRegistrar::class, function ($app) {
            return new ResourceRegistrar($app[Router::class]);
        });
    }

    /**
     * Register the URL generator.
     */
    protected function registerUrlGenerator()
    {
        $this->app->singleton('microservice.url', function ($app) {
            $url = new UrlGenerator($app['request']);

            $url->setRouteCollections($app[Router::class]->getRoutes());

            return $url;
        });
    }

    /**
     * Get the URL generator request rebinder.
     *
     * @return \Closure
     */
    private function requestRebinder()
    {
        return function ($app, $request) {
            $app['microservice.url']->setRequest($request);
        };
    }
}
