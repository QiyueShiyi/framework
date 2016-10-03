<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-03 02:43
 */
namespace Notadd\Foundation\Console;
use Illuminate\Container\Container;
use Notadd\Foundation\Console\Commands\OptimizeCommand;
use Notadd\Foundation\Console\Events\CommandRegister;
use Symfony\Component\Console\Application as SymfonyApplication;
/**
 * Class Application
 * @package Notadd\Foundation\Console
 */
class Application extends SymfonyApplication {
    /**
     * @var \Illuminate\Container\Container
     */
    protected $container;
    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;
    /**
     * @var \Notadd\Foundation\Console\Application
     */
    protected static $instance;
    /**
     * Application constructor.
     * @param \Illuminate\Container\Container $container
     * @param string $name
     */
    public function __construct(Container $container, $name = 'Notadd') {
        parent::__construct($name, call_user_func([$container, 'version']));
        $this->container = $container;
        $this->events = $container->make('events');
    }
    /**
     * @param \Illuminate\Container\Container $container
     * @param string $name
     * @return \Notadd\Foundation\Console\Application
     */
    public static function getInstance(Container $container, $name = 'Notadd') {
        if(is_null(static::$instance)) {
            static::$instance = new static($container, $name);
            static::$instance->registerCommands();
            static::$instance->events->fire(new CommandRegister($container, static::$instance));
        }
        return static::$instance;
    }
    /**
     * @param \Notadd\Foundation\Console\Application|null $application
     * @return \Notadd\Foundation\Console\Application
     */
    public static function setInstance(Application $application = null) {
        return static::$instance = $application;
    }
    /**
     * @return void
     */
    protected function registerCommands() {
        static::$instance->register(static::$instance->container->make(OptimizeCommand::class));
    }
}