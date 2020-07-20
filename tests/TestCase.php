<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use dimonka2\flatform\FlatformServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;


use Illuminate\Support\Facades\Facade as Facade;


class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        $this->afterApplicationCreated(function () {
            $this->makeACleanSlate();
        });

        $this->beforeApplicationDestroyed(function () {
            $this->makeACleanSlate();
        });

        parent::setUp();

        Facade::setFacadeApplication(app());

    }

    public function makeACleanSlate()
    {
        Artisan::call('view:clear');

    }

    protected function getPackageProviders($app)
    {
        return [
            FlatformServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('view.paths', [
            __DIR__.'/../views',
            resource_path('views'),
        ]);
        $app['config']->set('flatform', require  __DIR__ . '\..\config\flatform.php');
        $app['config']->set('session.driver', 'file');
        $app['config']->set('app.key', 'base64:Hupx3yAySikrM2/edkZQNQHslgDWYfiBfCuSThJ5SK8=');
        $app['config']->set('flatform.test', 1);

    }

    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Http\Kernel', 'Tests\HttpKernel');
    }

    public function test_test_envirement()
    {
        $this->assertNotNull(app('flatform'));
    }
}
