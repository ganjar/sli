<?php

namespace SLI\Translate\OriginalProcessors;

/**
 * Interface OriginalProcessorInterface
 * @package SLI\Translate\OriginalProcessors
 */
interface OriginalProcessorInterface
{
    /**
     * @param string $original
     * @return string
     */
    public function process($original);
}