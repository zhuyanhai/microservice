<?php

namespace Zyh\MicroService\Provider;

use RuntimeException;
use Zyh\MicroService\Auth\Auth;
use Zyh\MicroService\Dispatcher;
use Zyh\MicroService\Http\Request;
use Zyh\MicroService\Http\Response;
use Zyh\MicroService\Console\Command;
use Zyh\MicroService\Exception\Handler as ExceptionHandler;

class ZyhServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setResponseStaticInstances();

        Request::setAcceptParser($this->app['Zyh\MicroService\Http\Parser\Accept']);

        $this->app->rebinding('microservice.routes', function ($app, $routes) {
            $app['microservice.url']->setRouteCollections($routes);
        });
    }

    protected function setResponseStaticInstances()
    {
        Response::setFormatters($this->config('formats'));
        Response::setEventDispatcher($this->app['events']);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        $this->registerClassAliases();

        $this->app->register(RoutingServiceProvider::class);

        $this->app->register(HttpServiceProvider::class);

        $this->registerExceptionHandler();

        $this->registerDispatcher();

        $this->registerAuth();

        $this->registerDocsCommand();

        if (class_exists('Illuminate\Foundation\Application', false)) {
            $this->commands([
                'Zyh\MicroService\Console\Command\Cache',
                'Zyh\MicroService\Console\Command\Routes',
            ]);
        }
    }

    /**
     * Register the configuration.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/microservice.php'), 'microservice');

        if (! $this->app->runningInConsole() && empty($this->config('prefix')) && empty($this->config('domain'))) {
            throw new RuntimeException('Unable to boot MicroserviceServiceProvider, configure an microservice domain or prefix.');
        }
    }

    /**
     * Register the class aliases.
     *
     * @return void
     */
    protected function registerClassAliases()
    {
        $aliases = [
            'Zyh\MicroService\Http\Request' => 'Zyh\MicroService\Contract\Http\Request',
            'microservice.dispatcher' => 'Zyh\MicroService\Dispatcher',
            'microservice.http.validator' => 'Zyh\MicroService\Http\RequestValidator',
            'microservice.http.response' => 'Zyh\MicroService\Http\Response\Factory',
            'microservice.router' => 'Zyh\MicroService\Routing\Router',
            'microservice.router.adapter' => 'Zyh\MicroService\Contract\Routing\Adapter',
            'microservice.auth' => 'Zyh\MicroService\Auth\Auth',
            'microservice.limiting' => 'Zyh\MicroService\Http\RateLimit\Handler',
            'microservice.url' => 'Zyh\MicroService\Routing\UrlGenerator',
            'microservice.exception' => ['Zyh\MicroService\Exception\Handler', 'Zyh\MicroService\Contract\Debug\ExceptionHandler'],
        ];

        foreach ($aliases as $key => $aliases) {
            foreach ((array) $aliases as $alias) {
                $this->app->alias($key, $alias);
            }
        }
    }

    /**
     * Register the exception handler.
     *
     * @return void
     */
    protected function registerExceptionHandler()
    {
        $this->app->singleton('microservice.exception', function ($app) {
            return new ExceptionHandler($app['Illuminate\Contracts\Debug\ExceptionHandler'], $this->config('errorFormat'), $this->config('debug'));
        });
    }

    /**
     * Register the internal dispatcher.
     *
     * @return void
     */
    public function registerDispatcher()
    {
        $this->app->singleton('microservice.dispatcher', function ($app) {
            $dispatcher = new Dispatcher($app, $app['files'], $app['Zyh\MicroService\Routing\Router'], $app['Zyh\MicroService\Auth\Auth']);

            $dispatcher->setSubtype($this->config('subtype'));
            $dispatcher->setStandardsTree($this->config('standardsTree'));
            $dispatcher->setPrefix($this->config('prefix'));
            $dispatcher->setDefaultVersion($this->config('version'));
            $dispatcher->setDefaultDomain($this->config('domain'));
            $dispatcher->setDefaultFormat($this->config('defaultFormat'));

            return $dispatcher;
        });
    }

    /**
     * Register the auth.
     *
     * @return void
     */
    protected function registerAuth()
    {
        $this->app->singleton('microservice.auth', function ($app) {
            return new Auth($app['Zyh\MicroService\Routing\Router'], $app, $this->config('auth'));
        });
    }

    /**
     * Register the documentation command.
     *
     * @return void
     */
    protected function registerDocsCommand()
    {
        $this->app->singleton('Zyh\MicroService\Console\Command\Docs', function ($app) {
            return new Command\Docs(
                $app['Zyh\MicroService\Routing\Router'],
                $app['Dingo\Blueprint\Blueprint'],
                $app['Dingo\Blueprint\Writer'],
                $this->config('name'),
                $this->config('version')
            );
        });

        $this->commands(['Zyh\MicroService\Console\Command\Docs']);
    }
}
