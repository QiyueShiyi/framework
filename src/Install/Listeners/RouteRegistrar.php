<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-27 16:33
 */
namespace Notadd\Install\Listeners;
use Notadd\Foundation\Routing\Abstracts\AbstractRouteRegistrar;
use Notadd\Install\Controllers\InstallController;
/**
 * Class RouteRegister
 * @package Notadd\Install\Listeners
 */
class RouteRegistrar extends AbstractRouteRegistrar {
    /**
     * @return void
     */
    public function handle() {
        $this->router->resource('/', InstallController::class);
    }
}