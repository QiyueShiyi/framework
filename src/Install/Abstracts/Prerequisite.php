<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-27 17:03
 */
namespace Notadd\Install\Abstracts;
use Notadd\Install\Contracts\Prerequisite as PrerequisiteContract;
/**
 * Class Prerequisite
 * @package Notadd\Install\Abstracts
 */
abstract class Prerequisite implements PrerequisiteContract {
    /**
     * @var array
     */
    protected $errors = [];
    /**
     * @return void
     */
    abstract public function check();
    /**
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }
}