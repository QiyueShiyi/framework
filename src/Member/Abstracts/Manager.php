<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-11-01 17:29
 */
namespace Notadd\Foundation\Member\Abstracts;

use Illuminate\Events\Dispatcher;
use Illuminate\Routing\Router;

/**
 * Class Manager.
 */
abstract class Manager
{
    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;

    /**
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Manager constructor.
     *
     * @param \Illuminate\Events\Dispatcher $events
     * @param \Illuminate\Routing\Router    $router
     */
    public function __construct(Dispatcher $events, Router $router)
    {
        $this->events = $events;
        $this->router = $router;
    }

    /**
     * TODO: Method delete Description
     *
     * @param int  $id
     * @param bool $force
     *
     * @return mixed
     */
    abstract public function delete(int $id, $force = false);

    /**
     * TODO: Method find Description
     *
     * @param int $id
     *
     * @return mixed
     */
    abstract public function find(int $id);

    /**
     * TODO: Method init Description
     */
    public function init()
    {
    }

    /**
     * TODO: Method store Description
     *
     * @param array $data
     *
     * @return mixed
     */
    abstract public function store(array $data);

    /**
     * TODO: Method update Description
     *
     * @param int   $id
     * @param array $data
     * @param bool  $force
     *
     * @return mixed
     */
    abstract public function update(int $id, array $data, $force = false);
}
