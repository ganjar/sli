<?php

namespace SLI\Sources;

abstract class SourceAbstract implements SourceInterface
{
    /**
     * @param array  $phrases
     * @param string $languageAlias
     * @return array
     */
    public function getTranslates(array $phrases, $languageAlias)
    {
        $translatePhrases = [];
        foreach ($phrases as $phrase) {
            $translatePhrases[] = $this->getTranslate($phrase, $languageAlias);
        }

        return $translatePhrases;
    }
}