<?php

namespace Zyh\MicroService\Contract\Http\RateLimit;

use Zyh\MicroService\Http\Request;
use Illuminate\Container\Container;

interface HasRateLimiter
{
    /**
     * 获取一个可用的阀值器
     *
     * @param \Illuminate\Container\Container $app
     * @param \Zyh\MicroService\Http\Request         $request
     *
     * @return string
     */
    public function getRateLimiter(Container $app, Request $request);
}
