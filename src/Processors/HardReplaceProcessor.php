<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI\Processors;

/**
 * This processor only replace all occurrences.
 * For example: you may replace image url from /logo.png to /ru_logo.png
 * Case sensitive search!
 * Class HardReplaceProcessor
 * @package SLI\Processors
 */
class HardReplaceProcessor extends ProcessorAbstract
{
    protected $replacements = [];

    /**
     * @return array
     */
    public function getReplacements()
    {
        return $this->replacements;
    }

    /**
     * @param array $replacements
     * @return $this
     */
    public function setReplacements(array $replacements)
    {
        $this->replacements = $replacements;

        return $this;
    }

    /**
     * @param $search
     * @param $replace
     * @return $this
     */
    public function addReplacement($search, $replace)
    {
        $this->replacements[$search] = $replace;

        return $this;
    }

    /**
     * @param string $buffer
     * @param string $cleanBuffer
     * @return string
     */
    public function process($buffer, $cleanBuffer)
    {
        return strtr($buffer, $this->getReplacements());
    }
}