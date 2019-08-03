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
     * @return SLI
     */
    public function setProcessors($processors)
    {
        $this->processors = $processors;

        return $this;
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
     * @return SLI
     */
    public function setBuffer(Buffer $buffer)
    {
        $this->buffer = $buffer;

        return $this;
    }
}