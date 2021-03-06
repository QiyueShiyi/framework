<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-10-28 14:00
 */
namespace Notadd\Foundation\Passport\Listeners;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\ApiTokenCookieFactory;
use Notadd\Foundation\Passport\Controllers\AccessTokenController;
use Notadd\Foundation\Passport\Controllers\AuthorizationController;
use Notadd\Foundation\Passport\Controllers\ClientsController;
use Notadd\Foundation\Routing\Abstracts\RouteRegistrar;

/**
 * Class RouterRegistrar.
 */
class RouterRegistrar extends RouteRegistrar
{
    /**
     * Handle Route Registrar.
     */
    public function handle()
    {
        $this->router->group(['prefix' => 'oauth'], function () {
            $this->router->post('access', AccessTokenController::class . '@issueToken');
        });
        $this->router->group(['middleware' => ['web', 'auth'], 'prefix' => 'oauth'], function () {
            $this->router->delete('access/authorize', AuthorizationController::class . '@deny');
            $this->router->resource('authorize', AuthorizationController::class);
            $this->router->resource('clients', ClientsController::class);
            $this->router->post('refresh', function (ApiTokenCookieFactory $cookieFactory, Request $request) {
                return (new Response('Refreshed.'))->withCookie($cookieFactory->make($request->user()->getKey(),
                    $request->session()->token()));
            });
            $this->router->resource('access/token', AccessTokenController::class, [
                'only' => ['index', 'store'],
            ]);
        });
    }
}
