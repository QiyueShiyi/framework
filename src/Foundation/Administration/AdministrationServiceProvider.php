<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-10-21 13:24
 */
namespace Notadd\Foundation\Administration;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;
/**
 * Class AdministrationServiceProvider
 * @package Notadd\Admin
 */
class AdministrationServiceProvider extends ServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        $this->app->make('events')->listen(RouteMatched::class, function() {
            if($this->app->isInstalled()) {
                $administration = $this->app->make(Administration::class);
                if(is_null($administration->getAdministrator())) {
                    throw new \Exception("Administrator must be register!");
                }
            }
        });
    }
    /**
     * @return void
     */
    public function register() {
        $this->app->singleton('administration', function($app) {
            return new Administration($app, $app['events']);
        });
    }
}