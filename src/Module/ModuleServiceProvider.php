<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-12-02 20:29
 */
namespace Notadd\Foundation\Module;

use Illuminate\Support\ServiceProvider;
use Notadd\Foundation\Module\Commands\GenerateCommand;
use Notadd\Foundation\Module\Commands\ListCommand;

/**
 * Class ModuleServiceProvider.
 */
class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Boot service provider.
     */
    public function boot()
    {
        collect($this->app->make('module')->getModules())->each(function (Module $module, $path) {
            if ($this->app->make('files')->isDirectory($path) && is_string($module->getEntry())) {
                $this->app->register($module->getEntry());
            }
        });
        $this->commands([
            GenerateCommand::class,
            ListCommand::class
        ]);
    }

    /**
     * Register for service provider.
     */
    public function register()
    {
        $this->app->singleton('module', function ($app) {
            return new ModuleManager($app, $app['events'], $app['files']);
        });
    }
}
