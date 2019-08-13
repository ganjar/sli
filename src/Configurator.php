<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI;


use function is_null;
use Psr\Log\LoggerInterface;
use SLI\Exceptions\SliConfiguratorException;
use SLI\Language\LanguageInterface;
use SLI\Processors\ProcessorInterface;
use SLI\Sources\SourceInterface;

/**
 * Class Configurator
 * @package SLI
 */
class Configurator
{
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

    /**
     * @var Translate
     */
    protected $translate;

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
     * @return ProcessorInterface[]
     */
    public function getProcessors()
    {
        return $this->processors;
    }

    /**
     * @param ProcessorInterface[] $processors
     * @return $this
     */
    public function setProcessors($processors)
    {
        $this->processors = $processors;

        return $this;
    }

    /**
     * Add translate processor
     * @param ProcessorInterface $processor
     * @throws SliConfiguratorException
     */
    public function addProcessor(ProcessorInterface $processor)
    {
        $processor->setTranslate($this->getTranslate());
        $this->processors[] = $processor;
    }

    /**
     * @return SourceInterface
     * @throws SliConfiguratorException
     */
    public function getSource()
    {
        if (!$this->source) {
            throw new SliConfiguratorException('Source is not initialized');
        }

        return $this->source;
    }

    /**
     * @param SourceInterface $source
     * @return $this
     */
    public function setSource(SourceInterface $source)
    {
        $this->source = $source;

        return $this;

    }

    /**
     * @return LanguageInterface
     * @throws SliConfiguratorException
     */
    public function getLanguage()
    {
        if (!$this->language) {
            throw new SliConfiguratorException('Language is not initialized');
        }

        return $this->language;
    }

    /**
     * @param LanguageInterface $language
     * @return $this
     */
    public function setLanguage(LanguageInterface $language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return Buffer
     */
    public function getBuffer()
    {
        if (is_null($this->buffer)) {
            $this->setBuffer(new Buffer());
        }

        return $this->buffer;
    }

    /**
     * @param Buffer $buffer
     * @return $this
     */
    public function setBuffer(Buffer $buffer)
    {
        $this->buffer = $buffer;

        return $this;
    }

    /**
     * @return Translate
     * @throws SliConfiguratorException
     */
    public function getTranslate()
    {
        if (is_null($this->translate)) {
            $translate = new Translate($this->getLanguage(), $this->getSource(), $this->getEvent());
            $this->setTranslate($translate);
        }
        return $this->translate;
    }

    /**
     * @param Translate $translate
     * @return $this
     */
    public function setTranslate(Translate $translate)
    {
        $this->translate = $translate;

        return $this;
    }
}