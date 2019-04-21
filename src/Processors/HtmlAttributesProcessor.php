<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI\Processors;

/**
 * Class HtmlAttributesProcessor
 * @package SLI\Processors
 */
class HtmlAttributesProcessor extends ProcessorAbstract
{

    protected $allowAttributes = [];

    /**
     * @return array
     */
    public function getAllowAttributes()
    {
        return $this->allowAttributes;
    }

    /**
     * @param array $allowAttributes
     */
    public function setAllowAttributes($allowAttributes)
    {
        $this->allowAttributes = $allowAttributes;
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