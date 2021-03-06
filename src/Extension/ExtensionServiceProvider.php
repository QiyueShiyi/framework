<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-29 14:06
 */
namespace Notadd\Foundation\Extension;

use Illuminate\Support\ServiceProvider;
use Notadd\Foundation\Extension\Commands\ListCommand;

/**
 * Class ExtensionServiceProvider.
 */
class ExtensionServiceProvider extends ServiceProvider
{
    /**
     * Boot service provider.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function boot()
    {
        $this->app->make('extension')->getExtensions()->each(function (Extension $extension, $path) {
            if ($this->app->make('files')->isDirectory($path) && is_string($extension->getEntry())) {
                $this->app->register($extension->getEntry());
            }
        });
        $this->commands([
            ListCommand::class,
        ]);
    }

    /**
     * Register for service provider.
     */
    public function register()
    {
        $this->app->singleton('extension', function ($app) {
            return new ExtensionManager($app, $app['events'], $app['files']);
        });
    }
}
