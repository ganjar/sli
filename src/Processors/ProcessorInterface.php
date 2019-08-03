<?php

namespace SLI\Processors;

use SLI\Translate;

/**
 * Interface ProcessorInterface
 * @package SLI\Processors
 */
interface ProcessorInterface
{
    /**
     * @param Translate $translate
     * @return $this
     */
    public function setTranslate(Translate $translate);

    /**
     * @return Translate
     */
    public function getTranslate();

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