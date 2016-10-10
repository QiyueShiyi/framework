<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-26 13:46
 */
namespace Notadd\Foundation\Http\Listeners;
use Notadd\Foundation\Http\Controllers\IndexController;
use Notadd\Foundation\Routing\Abstracts\RouteRegistrar as AbstractRouteRegistrar;
/**
 * Class RouteRegister
 * @package Notadd\Foundation\Http\Listeners
 */
class RouteRegistrar extends AbstractRouteRegistrar {
    /**
     * @return void
     */
    public function handle() {
        $this->router->get('/', IndexController::class . '@index');
    }
}