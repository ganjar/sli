<?php

namespace SLI\Translate\Sources;

use SLI\Translate\Language\LanguageInterface;

class YandexSource extends SourceAbstract
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * YandexSource constructor.
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string            $phrase
     * @param LanguageInterface $language
     * @return string
     */
    public function getTranslate($phrase, LanguageInterface $language)
    {
        // TODO: Implement getTranslate() method.
    }
}