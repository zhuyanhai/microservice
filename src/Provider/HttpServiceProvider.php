<?php

namespace Zyh\MicroService\Provider;

use Zyh\MicroService\Auth\Auth;
use Zyh\MicroService\Routing\Router;
use Zyh\MicroService\Http\Validation;
use Zyh\MicroService\Http\Middleware;
use Zyh\MicroService\Http\RequestValidator;
use Zyh\MicroService\Http\RateLimit\Handler;
use Zyh\MicroService\Http\Validation\Accept;
use Zyh\MicroService\Http\Validation\Domain;
use Zyh\MicroService\Http\Validation\Prefix;
use Zyh\MicroService\Http\Middleware\Request;
use Zyh\MicroService\Http\Middleware\RateLimit;
use Zyh\MicroService\Contract\Debug\ExceptionHandler;
use Zyh\MicroService\Http\Middleware\PrepareController;
use Zyh\MicroService\Http\Parser\Accept as AcceptParser;
use Zyh\MicroService\Http\Middleware\Auth as AuthMiddleware;
use Zyh\MicroService\Http\Response\Factory as ResponseFactory;
use Zyh\MicroService\Http\RateLimit\Handler as RateLimitHandler;

class HttpServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRateLimiting();

        $this->registerHttpValidation();

        $this->registerHttpParsers();

        $this->registerResponseFactory();

        $this->registerMiddleware();
    }

    /**
     * Register the rate limiting.
     *
     * @return void
     */
    protected function registerRateLimiting()
    {
        $this->app->singleton('microservice.limiting', function ($app) {
            return new RateLimitHandler($app, $app['cache'], $this->config('throttling'));
        });
    }

    /**
     * Register the HTTP validation.
     *
     * @return void
     */
    protected function registerHttpValidation()
    {
        $this->app->singleton('microservice.http.validator', function ($app) {
            return new RequestValidator($app);
        });

        $this->app->singleton(Domain::class, function ($app) {
            return new Validation\Domain($this->config('domain'));
        });

        $this->app->singleton(Prefix::class, function ($app) {
            return new Validation\Prefix($this->config('prefix'));
        });

        $this->app->singleton(Accept::class, function ($app) {
            return new Validation\Accept(
                $this->app[AcceptParser::class],
                $this->config('strict')
            );
        });
    }

    /**
     * Register the HTTP parsers.
     *
     * @return void
     */
    protected function registerHttpParsers()
    {
        $this->app->singleton(AcceptParser::class, function ($app) {
            return new AcceptParser(
                $this->config('standardsTree'),
                $this->config('subtype'),
                $this->config('version'),
                $this->config('defaultFormat')
            );
        });
    }

    /**
     * Register the response factory.
     *
     * @return void
     */
    protected function registerResponseFactory()
    {
        $this->app->singleton('microservice.http.response', function ($app) {
            return new ResponseFactory($app[Factory::class]);
        });
    }

    /**
     * Register the middleware.
     *
     * @return void
     */
    protected function registerMiddleware()
    {
        $this->app->singleton(Request::class, function ($app) {
            $middleware = new Middleware\Request(
                $app,
                $app[ExceptionHandler::class],
                $app[Router::class],
                $app[RequestValidator::class],
                $app['events']
            );

            $middleware->setMiddlewares($this->config('middleware', false));

            return $middleware;
        });

        $this->app->singleton(AuthMiddleware::class, function ($app) {
            return new Middleware\Auth($app[Router::class], $app[Auth::class]);
        });

        $this->app->singleton(RateLimit::class, function ($app) {
            return new Middleware\RateLimit($app[Router::class], $app[Handler::class]);
        });

        $this->app->singleton(PrepareController::class, function ($app) {
            return new Middleware\PrepareController($app[Router::class]);
        });
    }
}
