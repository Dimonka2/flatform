<?php

namespace dimonka2\platform;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;

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
        AliasLoader::getInstance(config('platform.aliases', []));
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
        } else {
            Blade::directive('platform', function ($form) {
                return "<?php echo \dimonka2\platform\Platform::render($form); ?>";
            }); 
        }
    }

    protected function getConfigFile(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config'
            . DIRECTORY_SEPARATOR . self::config;
    }
}
