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
interface LanguageInterface
{
    /**
     * Language title (Русский, English)
     * @return string
     */
    function getTitle();

    /**
     * Language alias (ru, en)
     * @return string
     */
    function getAlias();

    /**
     * Get is language occur original language for content.
     * In this case - we does not need translation and always return original content.
     * @return bool
     */
    function getIsOriginal();
}