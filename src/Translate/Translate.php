<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */


namespace SLI\Translate;


use SLI\Event;
use SLI\Exceptions\SliException;
use SLI\Translate\Language\LanguageInterface;
use SLI\Translate\Sources\SourceInterface;

/**
 * Class Translate
 * @package SLI
 */
class Translate
{
    /**
     * @var LanguageInterface
     */
    protected $language;

    /**
     * @var SourceInterface
     */
    protected $source;

    /**
     * @var Event
     */
    protected $event;

    /**
     * Translate constructor.
     * @param LanguageInterface $language
     * @param SourceInterface   $source
     * @param Event             $event
     */
    public function __construct(LanguageInterface $language, SourceInterface $source, Event $event)
    {
        $this->language = $language;
        $this->source = $source;
        $this->event = $event;
    }

    /**
     * @return LanguageInterface
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @return SourceInterface
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param array         $phrases
     * @param \Closure|null $missingTranslationCallback - ($phrase, SLI $sli)
     * @return array
     */
    public function translateAll(array $phrases, \Closure $missingTranslationCallback = null)
    {
        $searchPhrases = $originalPhrases = [];
        foreach ($phrases as $phrase) {
            $searchPhrases[$phrase] = $this->originalPreProcessor($phrase);
            //Массив с обратным индексом оригинал => текст для поиска в источнике
            $originalPhrases[$searchPhrases[$phrase]] = $phrase;
        }

        $translates = $this->getSource()->getTranslates(
            $searchPhrases,
            $this->getLanguage()
        );
        foreach ($translates as $searchPhrase => &$translate) {
            if ($translate) {
                $translate = $this->translatePostProcessor($originalPhrases[$searchPhrase], $translate);
            } else {
                $this->getEvent()->trigger(Event::EVENT_MISSING_TRANSLATION, $searchPhrase, $this);
                if ($missingTranslationCallback) {
                    $translate = $missingTranslationCallback($searchPhrase, $this);
                }
            }
        }

        return $translates;
    }

    /**
     * Fast translate without buffers and processors
     * @param string        $phrase
     * @param \Closure|null $missingTranslationCallback - ($phrase, SLI $sli)
     * @return string
     * @throws SliException
     */
    public function translate($phrase, \Closure $missingTranslationCallback = null)
    {
        foreach ($this->translateAll([$phrase], $missingTranslationCallback) as $translate) {
            return $translate;
        }

        throw new SliException('Empty list of translated phrases');
    }

    /**
     * todo - Переделать на translate Pre & PostProcessors
     * Оработка текста перед
     * запросом в источник переводов
     * @var $text - string
     * @return string
     */
    protected function originalPreProcessor($text)
    {
        $text = preg_replace('!\d+!', '0', $text);
        $text = preg_replace('!\s+!s', ' ', $text);

        return trim($text);
    }

    /**
     * Обработка полученного перевода
     * @param string $original
     * @param string $translate
     * @return string
     */
    protected function translatePostProcessor($original, $translate)
    {
        //Заменяем измененные не переводимые части в значении перевода
        preg_match_all('#(?:[\d])+#u', $original, $symbols);
        preg_match_all('#(?:[\d])+#u', $translate, $tSymbols);

        $symbols = $symbols[0];
        $tSymbols = $tSymbols[0];

        if (!empty($symbols)) {

            $sPos = 0;
            foreach ($symbols as $symbolKey => $symbol) {

                $sPos = strpos($translate, $tSymbols[$symbolKey], $sPos);

                if ($sPos !== false) {
                    $translate = substr_replace($translate, $symbol, $sPos,
                        strlen($tSymbols[$symbolKey]));
                    $sPos += strlen($symbol);
                }
            }
        }

        return $translate;
    }
}