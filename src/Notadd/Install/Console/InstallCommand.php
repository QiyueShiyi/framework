<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-11-29 00:50
 */
namespace Notadd\Install\Console;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application;
use Notadd\Foundation\Console\Command;
class InstallCommand extends Command {
    protected $dataSource;
    protected $application;
    protected $filesystem;
    public function __construct(Application $application, Filesystem $filesystem) {
        $this->application = $application;
        parent::__construct();
        $this->filesystem = $filesystem;
    }
    public function fire() {

    }
}