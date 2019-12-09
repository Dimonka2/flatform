<?php

namespace dimonka2\platform;

use Illuminate\Support\ServiceProvider;

class PlatformServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    private const config = 'platform.php';
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->getConfigFile() => config_path(self::config),
            ], 'config');
        }
    }

    protected function getConfigFile(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config'
            . DIRECTORY_SEPARATOR . self::config;
    }
}
