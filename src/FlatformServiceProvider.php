<?php

namespace dimonka2\flatform;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Builder;
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
            $this->commands([
                \dimonka2\flatform\Commands\TestCommand::class,
            ]);
        } else {
            Blade::directive(config('flatform.blade_directive', 'form'), function ($form) {
                return "<?php echo Flatform::render($form); ?>";
            });
            $this->loadViewsFrom(
                config('flatform.views_directory', __DIR__.'/../views'), 'flatform');


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
        $this->app->bind('flatform', FlatformService::class);
    }

    protected function getConfigFile(): string
    {
        return __DIR__ . '/../config/' . self::config;
    }
}
