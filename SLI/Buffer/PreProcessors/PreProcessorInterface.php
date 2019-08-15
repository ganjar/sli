<?php

namespace SLI\Buffer\PreProcessors;

/**
 * Interface PreProcessorInterface
 * @package SLI\PreProcessors
 */
interface PreProcessorInterface
{
    /**
     * @param string $content
     * @return string
     */
    public function process($content);
}