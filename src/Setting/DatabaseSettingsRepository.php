<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-10-24 10:09
 */
namespace Notadd\Foundation\Setting;

use Illuminate\Database\ConnectionInterface;
use Notadd\Foundation\Setting\Contracts\SettingsRepository as SettingsRepositoryContract;

/**
 * Class DatabaseSettingsRepository.
 */
class DatabaseSettingsRepository implements SettingsRepositoryContract
{
    /**
     * @var \Illuminate\Database\ConnectionInterface
     */
    protected $database;

    /**
     * DatabaseSettingsRepository constructor.
     *
     * @param \Illuminate\Database\ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->database = $connection;
    }

    /**
     * TODO: Method all Description
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->database->table('settings')->pluck('value', 'key');
    }

    /**
     * TODO: Method delete Description
     *
     * @param $keyLike
     */
    public function delete($keyLike)
    {
        $this->database->table('settings')->where('key', 'like', $keyLike)->delete();
    }

    /**
     * TODO: Method get Description
     *
     * @param      $key
     * @param null $default
     *
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        if (is_null($value = $this->database->table('settings')->where('key', $key)->value('value'))) {
            return $default;
        }

        return $value;
    }

    /**
     * TODO: Method set Description
     *
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $query = $this->database->table('settings')->where('key', $key);
        $method = $query->exists() ? 'update' : 'insert';
        $query->$method(compact('key', 'value'));
    }
}
