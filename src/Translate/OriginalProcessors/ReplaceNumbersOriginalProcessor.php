<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI\Translate\OriginalProcessors;


/**
 * Class ReplaceNumbersOriginalProcessor
 * @package SLI\Buffer\PreProcessors
 */
class ReplaceNumbersOriginalProcessor implements OriginalProcessorInterface
{

    /**
     * @param string $original
     * @return string
     */
    public function process($original)
    {
        return preg_replace('!\d+!', '0', $original);
    }
}