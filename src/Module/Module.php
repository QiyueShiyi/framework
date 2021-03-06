<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-12-13 21:06
 */
namespace Notadd\Foundation\Module;

/**
 * Class Module.
 */
class Module
{
    /**
     * @var string|array
     */
    protected $author;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $entry;

    /**
     * @var bool
     */
    protected $installed = false;

    /**
     * @var string
     */
    protected $name;

    /**
     * Module constructor.
     *
     * @param string $name
     */
    public function __construct($name = null)
    {
        $this->name = $name;
    }

    /**
     * Author of module.
     *
     * @return string|array
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Description of module.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Entry of module.
     *
     * @return string
     */
    public function getEntry(): string
    {
        return $this->entry;
    }

    /**
     * Name of module.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Module install status.
     *
     * @return bool
     */
    public function isInstalled(): bool
    {
        return $this->installed;
    }

    /**
     * Set module's author.
     *
     * @param string|array $author
     */
    public function setAuthor($author)
    {
        $author = collect($author)->transform(function($value) {
            if(is_array($value))
                return implode(' <', $value) . '>';
            return $value;
        });

        $this->author = $author->toArray();
    }

    /**
     * Set module's description.
     *
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Set module's entry.
     *
     * @param string $entry
     */
    public function setEntry(string $entry)
    {
        $this->entry = $entry;
    }

    /**
     * Set module's install status.
     *
     * @param bool $installed
     */
    public function setInstalled(bool $installed)
    {
        $this->installed = $installed;
    }

    /**
     * Set module's name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
