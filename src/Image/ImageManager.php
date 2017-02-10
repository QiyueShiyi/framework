<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, iBenchu.org
 * @datetime 2017-02-10 15:24
 */
namespace Notadd\Foundation\Image;

use Closure;
use Illuminate\Container\Container;
use Notadd\Foundation\Image\Exceptions\MissingDependencyException;
use Notadd\Foundation\Image\Exceptions\NotSupportedException;

/**
 * Class ImageManager.
 */
class ImageManager
{
    /**
     * @var array
     */
    public $config = [
        'driver' => 'gd',
    ];

    /**
     * ImageManager constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->checkRequirements();
        $this->configure($config);
    }

    /**
     * @param array $config
     *
     * @return $this
     */
    public function configure(array $config = [])
    {
        $this->config = array_replace($this->config, $config);
        $this->config['driver'] = Container::getInstance()->make('setting')->get('attachment.engine', 'gd');

        return $this;
    }

    /**
     * @param mixed $data
     *
     * @return \Notadd\Foundation\Image\Image
     */
    public function make($data)
    {
        return $this->createDriver()->init($data);
    }

    /**
     * @param int   $width
     * @param int   $height
     * @param mixed $background
     *
     * @return \Notadd\Foundation\Image\Image
     */
    public function canvas($width, $height, $background = null)
    {
        return $this->createDriver()->newImage($width, $height, $background);
    }

    /**
     * @param \Closure $callback
     * @param int      $lifetime
     * @param bool     $returnObj
     *
     * @return \Notadd\Foundation\Image\Image
     *
     * @throws MissingDependencyException
     */
    public function cache(Closure $callback, $lifetime = null, $returnObj = false)
    {
        if (class_exists('Notadd\\Image\\ImageCache')) {
            $imagecache = new ImageCache($this);
            if (is_callable($callback)) {
                $callback($imagecache);
            }

            return $imagecache->get($lifetime, $returnObj);
        }
        throw new MissingDependencyException('Please install package imagecache before running this function.');
    }

    /**
     * @return \Notadd\Foundation\Image\AbstractDriver
     *
     * @throws NotSupportedException
     */
    private function createDriver()
    {
        $drivername = ucfirst($this->config['driver']);
        $driverclass = sprintf('Notadd\\Image\\%s\\Driver', $drivername);
        if (class_exists($driverclass)) {
            return new $driverclass();
        }
        throw new NotSupportedException("Driver ({$drivername}) could not be instantiated.");
    }

    /**
     * @throws \Notadd\Foundation\Image\Exceptions\MissingDependencyException
     */
    private function checkRequirements()
    {
        if (!function_exists('finfo_buffer')) {
            throw new MissingDependencyException('PHP Fileinfo extension must be installed/enabled to use Notadd Image.');
        }
    }
}
