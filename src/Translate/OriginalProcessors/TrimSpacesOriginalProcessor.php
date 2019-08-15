<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI\Translate\OriginalProcessors;

/**
 * Class ReplaceDuplicateSpacesOriginalProcessor
 * @package SLI\Translate\OriginalProcessors
 */
class TrimSpacesOriginalProcessor implements OriginalProcessorInterface
{
    /**
     * @param string $original
     * @return string
     */
    public function process($original)
    {
        return preg_replace('!\s+!s', ' ', trim($original));
    }
}