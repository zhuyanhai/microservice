<?php
/**
 * 认证服务提供者接口
 */

namespace Zyh\MicroService\Contract\Auth;

use Illuminate\Http\Request;
use Zyh\MicroService\Routing\Route;

interface Provider
{
    /**
     * 请求身份验证并且返回身份验证用户实例
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zyh\MicroService\Routing\Route $route
     *
     * @return mixed
     */
    public function authenticate(Request $request, Route $route);
}
