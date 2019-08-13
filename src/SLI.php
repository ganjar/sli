<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI;

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
     * Process all buffers and clear stack
     * @param $content
     * @return mixed
     * @throws Exceptions\SliConfiguratorException
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
     * @param $buffer
     * @return string
     */
    public function process($buffer)
    {
        $cleanBuffer = $buffer;
        foreach ($this->getConfigurator()->getPreProcessors() as $preProcessor) {
            $cleanBuffer = $preProcessor->process($cleanBuffer);
        }

        foreach ($this->getConfigurator()->getProcessors() as $processor) {
            $buffer = $processor->process($buffer, $cleanBuffer);
        }

        return $buffer;
    }

    /**
     * @return Buffer
     */
    public function getBuffer()
    {
        return $this->getConfigurator()->getBuffer();
    }

    /**
     * @return Translate
     * @throws Exceptions\SliConfiguratorException
     */
    public function getTranslate()
    {
        return $this->getConfigurator()->getTranslate();
    }
}