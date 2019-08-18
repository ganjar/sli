<?php

namespace SLI\Translate\Sources;

use SLI\Translate\Language\LanguageInterface;

interface SourceInterface
{
    /**
     * @param string            $phrase
     * @param LanguageInterface $language
     * @return string
     */
    public function getTranslate($phrase, LanguageInterface $language);

    /**
     * Get an array with original phrases as a key
     * and translated into a value
     * @param array             $phrases
     * @param LanguageInterface $language
     * @return array
     */
    public function getTranslates(array $phrases, LanguageInterface $language);

    /**
     * @param LanguageInterface $language
     * @param string            $original
     * @param string            $translate
     */
    public function saveTranslate(LanguageInterface $language, $original, $translate);

    /**
     * Delete original and all translated phrases
     * @param string $original
     */
    public function delete($original);
}