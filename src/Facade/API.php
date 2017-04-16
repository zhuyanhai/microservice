<?php

namespace Zyh\MicroService\Facade;

use Zyh\MicroService\Http\InternalRequest;
use Illuminate\Support\Facades\Facade;

class MicroService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'microservice.dispatcher';
    }

    /**
     * Bind an exception handler.
     *
     * @param callable $callback
     *
     * @return void
     */
    public static function error(callable $callback)
    {
        return static::$app['microservice.exception']->register($callback);
    }

    /**
     * Get the authenticator.
     *
     * @return \Zyh\MicroService\Auth\Auth
     */
    public static function auth()
    {
        return static::$app['microservice.auth'];
    }

    /**
     * Get the authenticated user.
     *
     * @return \Illuminate\Auth\GenericUser|\Illuminate\Database\Eloquent\Model
     */
    public static function user()
    {
        return static::$app['microservice.auth']->user();
    }

    /**
     * Determine if a request is internal.
     *
     * @return bool
     */
    public static function internal()
    {
        return static::$app['microservice.router']->getCurrentRequest() instanceof InternalRequest;
    }

    /**
     * Get the microservice router instance.
     *
     * @return \Zyh\MicroService\Routing\Router
     */
    public static function router()
    {
        return static::$app['microservice.router'];
    }
}
