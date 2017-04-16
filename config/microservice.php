<?php
/**
 * 定义微服务的配置
 *
 * todo
 */
return [

    /**
     * 标准树 HTTP协议中
     *
     * 例如：application/x.SUBTYPE.v1+json
     * x=standardsTree
     */
    'standardsTree' => env('MICROSERVICE_STANDARDS_TREE', 'micro'),

    /**
     * api 子类型，将跟随在 standards tree 后面
     *
     * 例如：application/x.SUBTYPE.v1+json
     * SUBTYPE=subtype
     */
    'subtype' => env('MICROSERVICE_SUBTYPE', ''),

    /**
     * 默认的 api 版本
     */
    'version' => env('MICROSERVICE_VERSION', 'v1'),

    /**
     * 默认的 api 前缀
     *
     * 与路由有关
     */
    'prefix' => env('MICROSERVICE_PREFIX', null),

    /**
     * 默认的 api 域名
     *
     * 与路由有关
     */
    'domain' => env('MICROSERVICE_DOMAIN', null),

    /**
     * 名字
     */
    'name' => env('MICROSERVICE_NAME', null),

    /**
     * 支持有条件的请求，添加一个 ETag 到任何的返回头中。下次请求时会检查并返回 304。这样能在组或路由上启用或禁用
     */
    'conditionalRequest' => env('MICROSERVICE_CONDITIONAL_REQUEST', true),

    /**
     * 严格模式会校验客户端发来的每一个请求头，
     */
    'strict' => env('MICROSERVICE_STRICT', false),

    /**
     * 调试模式，在错误返回的结果上将返回更多更详细的信息
     */
    'debug' => env('MICROSERVICE_DEBUG', false),

    /**
     * 错误格式，如果异常不捕获，将产生一个默认错误信息，任何没有替换的相应值的键将被删除
     */
    'errorFormat' => [
        'message' => ':message',
        'errors' => ':errors',
        'code' => ':code',
        'status_code' => ':status_code',
        'debug' => ':debug',
    ],

    /**
     * qpi 中间件数组，中间件将被应用到所有请求上
     *
     */
    'middleware' => [
    ],

    /**
     * 认证授权服务提供者数组
     */
    'auth' => [
    ],

    /**
     * 频率限制的阀值
     */
    'throttling' => [
    ],

    /**
     * 返回值默认格式
     */
    'defaultFormat' => env('MICROSERVICE_DEFAULT_FORMAT', 'json'),

    /**
     * 格式处理类
     */
    'formats' => [
        'json' => Zyh\MicroService\Http\Response\Format\Json::class,
    ]
];