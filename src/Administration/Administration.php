<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-10-25 17:47
 */
namespace Notadd\Foundation\Administration;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use InvalidArgumentException;
use Notadd\Foundation\Administration\Abstracts\Administrator;

/**
 * Class Administration.
 */
class Administration
{
    /**
     * @var \Notadd\Foundation\Administration\Abstracts\Administrator
     */
    protected $administrator;

    /**
     * @var \Illuminate\Container\Container
     */
    protected $container;

    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;

    /**
     * Administration constructor.
     *
     * @param \Illuminate\Container\Container $container
     * @param \Illuminate\Events\Dispatcher   $events
     */
    public function __construct(Container $container, Dispatcher $events)
    {
        $this->container = $container;
        $this->events = $events;
    }

    /**
     * Get administrator.
     *
     * @return \Notadd\Foundation\Administration\Abstracts\Administrator
     */
    public function getAdministrator()
    {
        return $this->administrator;
    }

    /**
     * Status of administrator's instance.
     *
     * @return bool
     */
    public function hasAdministrator()
    {
        return is_null($this->administrator) ? false : true;
    }

    /**
     * Set administrator instance.
     *
     * @param \Notadd\Foundation\Administration\Abstracts\Administrator $administrator
     *
     * @throws \InvalidArgumentException
     */
    public function setAdministrator(Administrator $administrator)
    {
        if (is_object($this->administrator)) {
            throw new InvalidArgumentException('Administrator has been Registered!');
        }
        if ($administrator instanceof Administrator) {
            $this->administrator = $administrator;
            $this->administrator->init();
        } else {
            throw new InvalidArgumentException('Administrator must be instanceof ' . Administrator::class . '!');
        }
    }
}
