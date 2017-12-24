<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI\Processors;

/**
 * Class HtmlTagProcessor
 * @package SLI\Processors
 */
class HtmlTagProcessor extends ProcessorAbstract
{
    protected $ignoreTags = [];

    /**
     * @return array
     */
    public function getIgnoreTags()
    {
        return $this->ignoreTags;
    }

    /**
     * @param array $ignoreTags
     * @return HtmlTagProcessor
     */
    public function setIgnoreTags($ignoreTags)
    {
        $this->ignoreTags = $ignoreTags;

        return $this;
    }

    /**
     * @param string $buffer
     * @return string
     */
    public function process($buffer)
    {
        // TODO: Implement process() method.
    }
}