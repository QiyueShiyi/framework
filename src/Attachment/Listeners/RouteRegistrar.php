<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-11-02 15:55
 */
namespace Notadd\Foundation\Attachment\Listeners;

use Notadd\Foundation\Attachment\Controllers\AttachmentController;
use Notadd\Foundation\Attachment\Controllers\CdnController;
use Notadd\Foundation\Attachment\Controllers\StorageController;
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
        $this->router->group(['middleware' => ['auth:api', 'web'], 'prefix' => 'api/attachment'], function () {
            $this->router->post('/', AttachmentController::class . '@handle');
            $this->router->post('cdn', CdnController::class . '@handle');
            $this->router->post('storage', StorageController::class . '@handle');
        });
    }
}
