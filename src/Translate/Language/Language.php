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
    protected $alias;
    protected $title;
    protected $isOriginal = false;

    /**
     * Language constructor.
     * @param string $alias
     * @param string $title
     * @param bool   $isOriginal
     */
    public function __construct($alias, $title = '', $isOriginal = false)
    {
        $this->alias = $alias;
        $this->title = $title;
        $this->isOriginal = $isOriginal;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return bool
     */
    public function getIsOriginal()
    {
        return $this->isOriginal;
    }
}