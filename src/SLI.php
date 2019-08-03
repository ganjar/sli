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
     * @var Configurator
     */
    protected $configurator;

    /**
     * SLI constructor.
     * @param Configurator $configurator
     */
    public function __construct(Configurator $configurator)
    {
        $this->configurator = $configurator;
    }

    /**
     * @return Configurator
     */
    public function getConfigurator()
    {
        return $this->configurator;
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

        $translates = $this->getConfigurator()->getSource()->getTranslates(
            $searchPhrases,
            $this->getConfigurator()->getLanguage()
        );
        foreach ($translates as $searchPhrase => &$translate) {
            if ($translate) {
                $translate = $this->translatePostProcessor($originalPhrases[$searchPhrase], $translate);
            } else {
                $this->getConfigurator()->getEvent()->trigger(SLI::EVENT_MISSING_TRANSLATION, $searchPhrase, $this);
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

    /**
     * Process all buffers and clear stack
     * @param $content
     * @return mixed
     */
    public function processAllBuffers($content)
    {
        $buffers = $this->getConfigurator()->getBuffer()->getBuffers();

        foreach ($buffers as $bufferKey => $buffer) {

            if (!$this->getConfigurator()->getLanguage()->getIsOriginal()) {
                $buffer = $this->process($buffer);
            }

            $content = str_replace(
                $this->getConfigurator()->getBuffer()->getBufferKey($bufferKey),
                $buffer,
                $content
            );
        }

        $this->getConfigurator()->getBuffer()->clear();

        return $content;
    }

    /**
     * Run all processors by content
     * @param $content
     * @return string
     */
    public function process($content)
    {
        foreach ($this->getConfigurator()->getProcessors() as $processor) {
            $content = $processor->process($content);
        }

        return $content;
    }
}