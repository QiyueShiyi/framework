<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-10-24 10:07
 */
namespace Notadd\Foundation\Setting;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Notadd\Foundation\Setting\Listeners\CsrfTokenRegister;
use Notadd\Foundation\Setting\Listeners\RouteRegistrar;

/**
 * Class SettingServiceProvider.
 */
class SettingServiceProvider extends ServiceProvider
{
    /**
     * Boot service provider.
     */
    public function boot()
    {
        $this->app->make(Dispatcher::class)->subscribe(CsrfTokenRegister::class);
        $this->app->make(Dispatcher::class)->subscribe(RouteRegistrar::class);
    }

    /**
     * Register for service provider.
     */
    public function register()
    {
        $this->app->singleton('setting', function () {
            return new MemoryCacheSettingsRepository(new DatabaseSettingsRepository($this->app->make('db.connection')));
        });
    }
}
