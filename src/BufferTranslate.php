<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI;

use SLI\Buffer\Buffer;
use SLI\Buffer\PreProcessors\PreProcessorInterface;
use SLI\Buffer\Processors\ProcessorInterface;
use SLI\Exceptions\TranslateNotDefinedException;
use SLI\Translate\Translate;

/**
 * Class BufferTranslate
 * @package SLI
 */
class BufferTranslate
{
    /**
     * @var Translate
     */
    protected $translate;

    /**
     * @var Buffer
     */
    protected $buffer;

    /**
     * @var PreProcessorInterface[]
     */
    protected $preProcessors = [];

    /**
     * @var ProcessorInterface[]
     */
    protected $processors = [];
    
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
     * @throws TranslateNotDefinedException
     */
    public function addProcessor(ProcessorInterface $processor)
    {
        $processor->setTranslate($this->getTranslate());
        $this->processors[] = $processor;
    }

    /**
     * @return PreProcessorInterface[]
     */
    public function getPreProcessors()
    {
        return $this->preProcessors;
    }

    /**
     * @param PreProcessorInterface[] $preProcessors
     * @return $this
     */
    public function setPreProcessors($preProcessors)
    {
        $this->preProcessors = $preProcessors;

        return $this;
    }

    /**
     * Add translate processor
     * @param PreProcessorInterface $preProcessor
     */
    public function addPreProcessor(PreProcessorInterface $preProcessor)
    {
        $this->preProcessors[] = $preProcessor;
    }

    /**
     * @return Translate
     * @throws TranslateNotDefinedException
     */
    public function getTranslate()
    {
        if (is_null($this->translate)) {
            throw new TranslateNotDefinedException('Translate object must be defined');
        }
        return $this->translate;
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
     * Process all buffers and clear stack
     * @param $content
     * @return mixed
     * @throws TranslateNotDefinedException
     */
    public function translateAllAndReplaceInSource($content)
    {
        $buffers = $this->getBuffer()->getBuffers();

        foreach ($buffers as $bufferKey => $buffer) {

            if (!$this->getTranslate()->getLanguage()->getIsOriginal()) {
                $buffer = $this->translateBuffer($buffer);
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
     * Run all processors by content
     * @param $buffer
     * @return string
     */
    public function translateBuffer($buffer)
    {
        $cleanBuffer = $buffer;
        foreach ($this->getPreProcessors() as $preProcessor) {
            $cleanBuffer = $preProcessor->process($cleanBuffer);
        }

        foreach ($this->getProcessors() as $processor) {
            $buffer = $processor->process($buffer, $cleanBuffer);
        }

        return $buffer;
    }
}