<?php

namespace SLI\Translate\TranslateProcessors;

/**
 * Interface TranslateProcessorInterface
 * @package SLI\Translate\TranslateProcessors
 */
interface TranslateProcessorInterface
{
    /**
     * @param string $original
     * @param string $translate
     * @return string - translate string
     */
    public function process($original, $translate);
}