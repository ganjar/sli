<?php

namespace SLI\Sources;

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
     * @param string $phrase
     * @param string $languageAlias
     * @return string
     */
    public function getTranslate($phrase, $languageAlias)
    {
        // TODO: Implement getTranslate() method.
    }
}