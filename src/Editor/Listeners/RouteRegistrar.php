<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, iBenchu.org
 * @datetime 2017-02-10 16:44
 */
namespace Notadd\Foundation\Editor\Listeners;

use Notadd\Foundation\Editor\Controllers\UEditorController;
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
        $this->router->group(['middleware' => ['web']], function () {
            $this->router->any('editor', UEditorController::class . '@handle');
        });
    }
}
