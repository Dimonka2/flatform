<?php

namespace dimonka2\flatform;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;

class FlatformServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    private const config = 'flatform.php';
    public function register()
    {
        AliasLoader::getInstance(config('flatform.aliases', []));
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
            Blade::directive('flatform', function ($form) {
            
                return "<?php echo \dimonka2\\flatform\Flatform::render($form); ?>";
            });
            $this->loadViewsFrom(__DIR__.'/../views', 'flatform');
        }
    }

    protected function getConfigFile(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config'
            . DIRECTORY_SEPARATOR . self::config;
    }
}
