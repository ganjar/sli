<?php

namespace SLI\Sources;

use SLI\Language\LanguageInterface;

interface SourceInterface
{
    /**
     * @param string            $phrase
     * @param LanguageInterface $language
     * @return string
     */
    public function getTranslate($phrase, LanguageInterface $language);

    /**
     * @param array             $phrases
     * @param LanguageInterface $language
     * @return array
     */
    public function getTranslates(array $phrases, LanguageInterface $language);
}