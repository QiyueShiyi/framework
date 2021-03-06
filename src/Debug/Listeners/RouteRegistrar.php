<?php
/**
 * Created by PhpStorm.
 * User: TwilRoad
 * Date: 2016/11/16 0016
 * Time: 13:54.
 */
namespace Notadd\Foundation\Debug\Listeners;

use Notadd\Foundation\Debug\Controllers\DebugController;
use Notadd\Foundation\Routing\Abstracts\RouteRegistrar as AbstractRouteRegistrar;

/**
 * Class RouteRegistrar.
 */
class RouteRegistrar extends AbstractRouteRegistrar
{
    /**
     * Handle Route Registrar.
     */
    public function handle()
    {
        $this->router->group(['middleware' => ['auth:api', 'web'], 'prefix' => 'api'], function () {
            $this->router->post('debug', DebugController::class . '@handle');
        });
    }
}
