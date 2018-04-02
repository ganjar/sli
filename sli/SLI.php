<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI;


use Psr\Log\LoggerInterface;
use SLI\Exceptions\SliLogicException;
use SLI\Language\LanguageInterface;
use SLI\Processors\ProcessorInterface;
use SLI\Sources\SourceInterface;

/**
 * Class SLI
 * @package SLI
 */
class SLI
{
    const EVENT_MISSING_TRANSLATION = 'missing_translation';

    /**
     * @var SourceInterface
     */
    protected $source;

    /**
     * @var LanguageInterface
     */
    protected $language;

    /**
     * @var Buffer
     */
    protected $buffer;

    /**
     * @var ProcessorInterface[]
     */
    protected $processors = [];

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Event
     */
    protected $event;

    public function __construct()
    {
        $this->on(self::EVENT_MISSING_TRANSLATION, function (SLI $sli, $phrase) {
            $sli->getLogger()->notice('SLI: Missing translation for phrase: ' . $phrase);
        });
    }

    /**
     * Add event
     * EVENT_MISSING_TRANSLATION - You may set callback with (SLI $sli, $phrase, ProcessorInterface $processor = null)
     * @param          $event
     * @param \Closure $callback - (SLI $sli, ...$data)
     */
    public function on($event, \Closure $callback)
    {
        $this->getEvent()->on($event, $callback);
    }

    /**
     * @return Event
     */
    public function getEvent()
    {
        if (!$this->event) {
            $this->setEvent(new Event());
        }

        return $this->event;
    }

    /**
     * @param Event $event
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        if (!$this->logger) {
            $this->setLogger(new \Psr\Log\NullLogger());
        }

        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Add translate processor
     * @param ProcessorInterface $processor
     */
    public function addProcessor(ProcessorInterface $processor)
    {
        $processor->setSli($this);
        $this->processors[] = $processor;
    }

    /**
     * @return SourceInterface
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param SourceInterface $source
     * @return SLI
     */
    public function setSource(SourceInterface $source)
    {
        $this->source = $source;

        return $this;

    }

    /**
     * @return LanguageInterface
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param LanguageInterface $language
     * @return SLI
     */
    public function setLanguage(LanguageInterface $language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Trigger custom event
     * @param       $event
     * @param array ...$data
     */
    public function trigger($event, ...$data)
    {
        $this->getEvent()->trigger($event, $this, ...$data);
    }

    /**
     * @param array         $phrases
     * @param \Closure|null $missingTranslationCallback - ($phrase, SLI $sli)
     * @return array
     */
    public function translateAll(array $phrases, \Closure $missingTranslationCallback = null)
    {
        $searchPhrases = $originalPhrases =[];
        foreach ($phrases as $phrase) {
            $searchPhrases[$phrase] = $this->originalPreProcessor($phrase);
            //Массив с обратным индексом оригинал => текст для поиска в источнике
            $originalPhrases[$searchPhrases[$phrase]] = $phrase;
        }

        $translates = $this->getSource()->getTranslates($searchPhrases, $this->getLanguage()->getAlias());
        foreach ($translates as $searchPhrase => &$translate) {
            if ($translate) {
                $translate = $this->translatePostProcessor($originalPhrases[$searchPhrase], $translate);
            } else {
                $this->trigger(SLI::EVENT_MISSING_TRANSLATION, $searchPhrase, $this);
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
     */
    public function translate($phrase, \Closure $missingTranslationCallback = null)
    {
        foreach ($this->translateAll([$phrase], $missingTranslationCallback) as $translate) {
            return $translate;
        }

        throw new SliLogicException('Empty list of translated phrases');
    }

    /**
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

        $symbols  = $symbols[0];
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

    /**
     * This method start global buffering.
     * Before render page - we replace all buffer keys to translated content
     */
    protected function startGlobalBuffering()
    {
        ob_start(function ($content) {
            return $this->processAllBuffers($content);
        });
    }

    /**
     * Process all buffers and clear stack
     * @param $content
     * @return mixed
     */
    protected function processAllBuffers($content)
    {
        $buffers = $this->getBuffer()->getBuffers();

        foreach ($buffers as $bufferKey => $buffer) {

            if (!$this->getLanguage()->getIsOriginal()) {
                $buffer = $this->process($buffer);
            }

            $content = str_replace(
                $this->getBuffer()->getBufferKey($bufferKey),
                $buffer,
                $content
            );
        }

        $this->getBuffer()->clear();

        return $content;
    }

    /**
     * @return Buffer
     */
    public function getBuffer()
    {
        if (is_null($this->buffer)) {
            $this->setBuffer(new Buffer());
            $this->startGlobalBuffering();
        }

        return $this->buffer;
    }

    /**
     * @param Buffer $buffer
     * @return SLI
     */
    public function setBuffer(Buffer $buffer)
    {
        $this->buffer = $buffer;

        return $this;
    }

    /**
     * Run all processors by content
     * @param $content
     * @return string
     */
    public function process($content)
    {
        foreach ($this->getProcessors() as $processor) {
            $content = $processor->process($content);
        }

        return $content;
    }

    /**
     * @return ProcessorInterface[]
     */
    public function getProcessors()
    {
        return $this->processors;
    }

    /**
     * @param ProcessorInterface[] $processors
     * @return SLI
     */
    public function setProcessors($processors)
    {
        $this->processors = $processors;

        return $this;
    }
}