<?php

namespace Zyh\MicroService\Event;

use Zyh\MicroService\Http\Request;
use Illuminate\Contracts\Container\Container;

class RequestWasMatched
{
    /**
     * Request instance.
     *
     * @var \Zyh\MicroService\Http\Request
     */
    public $request;

    /**
     * Application instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    public $app;

    /**
     * Create a new request was matched event.
     *
     * @param \Zyh\MicroService\Http\Request                   $request
     * @param \Illuminate\Contracts\Container\Container $app
     *
     * @return void
     */
    public function __construct(Request $request, Container $app)
    {
        $this->request = $request;
        $this->app = $app;
    }
}
