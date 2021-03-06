<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, iBenchu.org
 * @datetime 2017-02-10 18:48
 */
namespace Notadd\Foundation\Image\Imagick\Commands;

use Notadd\Foundation\Image\Commands\AbstractCommand;

/**
 * Class BackupCommand.
 */
class BackupCommand extends AbstractCommand
{
    /**
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $backupName = $this->argument(0)->value();
        $image->setBackup(clone $image->getCore(), $backupName);

        return true;
    }
}
