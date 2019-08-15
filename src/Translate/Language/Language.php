<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI\Translate\Language;

/**
 * @author GANJAR (Bohdan Rykhal)
 */
class Language implements LanguageInterface
{
    protected $id;
    protected $alias;
    protected $title;
    protected $isOriginal = false;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param $alias
     * @return $this
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsOriginal()
    {
        return $this->isOriginal;
    }

    /**
     * @param mixed $isOriginal
     * @return $this
     */
    public function setIsOriginal($isOriginal)
    {
        $this->isOriginal = $isOriginal;

        return $this;
    }
}