<?php

namespace SLI\PreProcessors;

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