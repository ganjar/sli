<?php

namespace SLI\Processors;

use SLI\SLI;

/**
 * Interface ProcessorInterface
 * @package SLI\Processors
 */
interface ProcessorInterface
{
    /**
     * @param SLI $sli
     * @return $this
     */
    public function setSli(SLI $sli);

    /**
     * @return SLI
     */
    public function getSli();

    /**
     * $missingTranslateCallback - function with 2 arguments ($phrase, $this)
     * $this - ProcessorInterface object.
     * $phrase - phrase, that does not have translation.
     * You can return a string that will be used as a translation
     *
     * @param \Closure $missingTranslateCallback
     * @return $this
     */
    public function setMissingTranslationCallback(\Closure $missingTranslateCallback);

    /**
     * @return \Closure|null
     */
    public function getMissingTranslationCallback();

    /**
     * @param string $buffer
     * @return string
     */
    public function process($buffer);
}