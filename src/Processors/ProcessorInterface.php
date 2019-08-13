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
     * @param string $buffer
     * @param string $cleanBuffer
     * @return string
     */
    public function process($buffer, $cleanBuffer);
}