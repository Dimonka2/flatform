<?php

namespace dimonka2\flatform;

use Illuminate\Routing\Router;
use dimonka2\flatform\Form\Context;
use dimonka2\flatform\Form\Renderer;
use Illuminate\Support\Facades\Blade;
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
    public const SINGLETON_BINDING_RENDERER = 'flatformRenderer';
    public const SINGLETON_BINDING_CONTEXT = 'flatformContext';

    private const config = 'flatform.php';
    public function register()
    {
        AliasLoader::getInstance(FlatformService::config('flatform.aliases', []));
    }

    protected function bootBladeRelateItems()
    {
        Blade::directive(FlatformService::config('flatform.blade_directive', 'form'), function ($form) {
            return "<?php echo Flatform::render($form); ?>";
        });
        $this->loadViewsFrom(
            FlatformService::config('flatform.views_directory', __DIR__.'/../views'), 'flatform');

        if(FlatformService::livewire() ) {
            \Livewire\Livewire::component('flatform.table', \dimonka2\flatform\Livewire\TableComponent::class);
            \Livewire\Livewire::component('flatform.table-row', \dimonka2\flatform\Livewire\TableRowComponent::class);
            \Livewire\Livewire::component('flatform.actions', \dimonka2\flatform\Livewire\ActionComponent::class);
            \Livewire\Livewire::component('flatform.form', \dimonka2\flatform\Livewire\FormComponent::class);
            \Livewire\Livewire::component('flatform.dropdown', \dimonka2\flatform\Livewire\DropdownComponent::class);
        }
        $this->registerMarcos();
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
            if(app()->runningUnitTests()) $this->bootBladeRelateItems();

        } else {
           $this->bootBladeRelateItems();

        }
        $this->app->bind('flatform', FlatformService::class);
        $this->app->singleton(self::SINGLETON_BINDING_RENDERER, function ($app) {
            return new Renderer();
        });
        $this->app->singleton(self::SINGLETON_BINDING_CONTEXT, function ($app) {
            return new Context();
        }); 
        $this->loadTranslationsFrom(__DIR__.'/../lang/', 'flatform');

        // register middleware
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('style', FlatformMiddleware::class);
    }

    protected function getConfigFile(): string
    {
        return __DIR__ . '/../config/' . self::config;
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
