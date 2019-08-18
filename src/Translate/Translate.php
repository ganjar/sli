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
use SLI\Translate\OriginalProcessors\OriginalProcessorInterface;
use SLI\Translate\Sources\SourceInterface;
use SLI\Translate\TranslateProcessors\TranslateProcessorInterface;

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
     * @var OriginalProcessorInterface[]
     */
    protected $originalProcessors = [];

    /**
     * @var TranslateProcessorInterface[]
     */
    protected $translateProcessors = [];

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
     * @return OriginalProcessorInterface[]
     */
    public function getOriginalProcessors()
    {
        return $this->originalProcessors;
    }

    /**
     * @param OriginalProcessorInterface[] $originalProcessors
     * @return $this
     */
    public function setOriginalProcessors($originalProcessors)
    {
        $this->originalProcessors = $originalProcessors;

        return $this;
    }

    /**
     * @param OriginalProcessorInterface $originalProcessor
     */
    public function addOriginalProcessor(OriginalProcessorInterface $originalProcessor)
    {
        $this->originalProcessors[] = $originalProcessor;
    }

    /**
     * @return TranslateProcessorInterface[]
     */
    public function getTranslateProcessors()
    {
        return $this->translateProcessors;
    }

    /**
     * @param TranslateProcessorInterface[] $translateProcessors
     * @return $this
     */
    public function setTranslateProcessors($translateProcessors)
    {
        $this->translateProcessors = $translateProcessors;

        return $this;
    }

    /**
     * @param TranslateProcessorInterface $translateProcessor
     */
    public function addTranslateProcessor(TranslateProcessorInterface $translateProcessor)
    {
        $this->translateProcessors[] = $translateProcessor;
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
            $searchPhrases[$phrase] = $this->originalProcess($phrase);
            $originalPhrases[$searchPhrases[$phrase]] = $phrase;
        }

        $translates = $this->getSource()->getTranslates(
            $searchPhrases,
            $this->getLanguage()
        );
        foreach ($translates as $searchPhrase => &$translate) {
            if ($translate !== '') {
                $translate = $this->translateProcess($originalPhrases[$searchPhrase], $translate);
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
     * @param LanguageInterface $language
     * @param string            $original
     * @param string            $translate
     */
    public function saveTranslate(LanguageInterface $language, $original, $translate)
    {
        $original = $this->originalProcess($original);
        $this->getSource()->saveTranslate($language, $original, $translate);
    }

    /**
     * Delete original and all translated phrases
     * @param $original
     */
    public function delete($original)
    {
        $this->getSource()->delete($original);
    }

    /**
     * @param $original
     * @return string
     */
    protected function originalProcess($original)
    {
        foreach ($this->getOriginalProcessors() as $originalProcessor) {
            $original = $originalProcessor->process($original);
        }

        return $original;
    }

    /**
     * @param string $original
     * @param string $translate
     * @return string
     */
    protected function translateProcess($original, $translate)
    {
        foreach ($this->getTranslateProcessors() as $translateProcessor) {
            $translate = $translateProcessor->process($original, $translate);
        }

        return $translate;
    }
}