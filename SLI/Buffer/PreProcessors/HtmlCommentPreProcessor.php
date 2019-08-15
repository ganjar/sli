<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI\Buffer\PreProcessors;

/**
 * Class HtmlCommentPreProcessor
 * @package SLI\Processors
 */
class HtmlCommentPreProcessor extends PreProcessorAbstract
{
    /**
     * @param string $content
     * @return string
     */
    public function process($content)
    {
        return preg_replace('#(<!--.*-->)#Usi', '', $content);
    }
}