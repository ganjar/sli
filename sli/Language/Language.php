<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI\Language;

/**
 * @author GANJAR (Bohdan Rykhal)
 */
class Language implements LanguageInterface
{
    protected $alias;
    protected $title;
    protected $isOriginal = false;

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
     * @return Language
     */
    public function setIsOriginal($isOriginal)
    {
        $this->isOriginal = $isOriginal;

        return $this;
    }
}