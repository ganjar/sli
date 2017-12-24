<?php

namespace SLI\Sources;

interface SourceInterface
{
    /**
     * @param string $phrase
     * @param string $languageAlias
     * @return string
     */
    public function getTranslate($phrase, $languageAlias);

    /**
     * @param array  $phrases
     * @param string $languageAlias
     * @return array
     */
    public function getTranslates(array $phrases, $languageAlias);
}