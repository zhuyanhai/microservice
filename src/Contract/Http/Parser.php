<?php

namespace Zyh\MicroService\Contract\Http;

use Illuminate\Http\Request as IlluminateRequest;

interface Parser
{
    /**
     * Parse an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function parse(IlluminateRequest $request);
}
