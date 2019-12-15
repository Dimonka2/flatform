<?php

namespace dimonka2\flatform;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use dimonka2\flatform\FlatformService;

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
            Blade::directive(config('flatform.blade_directive', 'form'), function ($form) {
                return "<?php echo Flatform::render($form); ?>";
            });
            $this->loadViewsFrom(
                config('flatform.views_directory', __DIR__.'/../views'), 'flatform');
        }
        $this->app->bind('flatform', FlatformService::class);
    }

    protected function getConfigFile(): string
    {
        return __DIR__ . '/../config/' . self::config;
    }
}
