<?php

namespace Zyh\MicroService\Contract\Http;

use Illuminate\Http\Request as IlluminateRequest;

interface Request
{
    /**
     * 从Illuminate request 实例创建一个请求实例
     *
     * @param \Illuminate\Http\Request $old
     *
     * @return \Zyh\MicroService\Http\Request
     */
    public function createFromIlluminate(IlluminateRequest $old);
}
