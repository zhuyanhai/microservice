<?php

namespace Zyh\MicroService\Console\Command;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Console\Kernel;

class Cache extends Command
{
    /**
     * 控制台命令签名
     *
     * @var string
     */
    public $signature = 'microservice:cache';

    /**
     * 控制台命令描述
     *
     * @var string
     */
    public $description = 'Create a route cache file for faster route registration';

    /**
     * 文件系统实例
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * 创建一个缓存命令实例
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;

        parent::__construct();
    }

    /**
     * 执行控制台命令
     *
     * @return mixed
     */
    public function handle()
    {
        $this->callSilent('route:clear');

        $app = $this->getFreshApplication();

        $this->call('route:cache');

        $routes = $app['microservice.router']->getAdapterRoutes();

        foreach ($routes as $collection) {
            foreach ($collection as $route) {
                $app['microservice.router.adapter']->prepareRouteForSerialization($route);
            }
        }

        $stub = "app('microservice.router')->setAdapterRoutes(unserialize(base64_decode('{{routes}}')));";
        $path = $this->laravel->getCachedRoutesPath();

        if (! $this->files->exists($path)) {
            $stub = "<?php\n\n$stub";
        }

        $this->files->append(
            $path,
            str_replace('{{routes}}', base64_encode(serialize($routes)), $stub)
        );
    }

    /**
     * 获取一个新的应用程序实例
     *
     * @return \Illuminate\Contracts\Container\Container
     */
    protected function getFreshApplication()
    {
        $app = require $this->laravel->basePath().'/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
