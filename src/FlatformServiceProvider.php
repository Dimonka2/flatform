<?php

namespace dimonka2\flatform;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use dimonka2\flatform\FlatformService;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

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
        AliasLoader::getInstance(FlatformService::config('flatform.aliases', []));
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
            $this->commands([
                \dimonka2\flatform\Commands\TestCommand::class,
            ]);
            $this->publishes([
                __DIR__.'/../lang' => resource_path('lang/vendor/flatform'),
            ]);
        } else {
            Blade::directive(FlatformService::config('flatform.blade_directive', 'form'), function ($form) {
                return "<?php echo Flatform::render($form); ?>";
            });
            $this->loadViewsFrom(
                FlatformService::config('flatform.views_directory', __DIR__.'/../views'), 'flatform');

            Route::group($this->routeConfig(), function () {
                $this->loadRoutesFrom(__DIR__.'/routes.php');
            });
            if(FlatformService::config('flatform.livewire.active')) {
                \Livewire\Livewire::component('flatform.table', \dimonka2\flatform\Livewire\TableComponent::class);
            }
            $this->registerMarcos();

        }
        $this->app->bind('flatform', FlatformService::class);
        $this->loadTranslationsFrom(__DIR__.'/../lang/', 'flatform');
    }

    protected function getConfigFile(): string
    {
        return __DIR__ . '/../config/' . self::config;
    }

    protected function routeConfig()
    {
        return [
            'namespace' => 'dimonka2\flatform\Http\Controllers',
            'as' => 'flatform.',
            'middleware' => FlatformService::config('flatform.actions.middleware'),
            'prefix' => 'flatform',
        ];
    }

    protected function registerMarcos()
    {
        // https://freek.dev/1182-searching-models-using-a-where-like-query-in-laravel
        Builder::macro('whereLike', function ($attributes, string $searchTerm) {
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach (array_wrap($attributes) as $attribute) {
                    $query->when(
                        str_contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            [$relationName, $relationAttribute] = explode('.', $attribute);

                            $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerm) {
                                $query->where($relationAttribute, 'LIKE', "%{$searchTerm}%");
                            });
                        },
                        function (Builder $query) use ($attribute, $searchTerm) {
                            $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                        }
                    );
                }
            });

            return $this;
        });
    }
}
