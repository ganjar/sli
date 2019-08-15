<?php

namespace SLI\Buffer\Processors;

use SLI\Translate\Translate;

/**
 * Interface ProcessorInterface
 * @package SLI\Buffer\Processors
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