<?php

if (! function_exists('version')) {
    /**
     * 设置版本号到microservice的url中
     *
     * @param string $version 例如 v1
     *
     * @return \Zyh\MicroService\Routing\UrlGenerator
     */
    function version($version)
    {
        return app('microservice.url')->version($version);
    }
}