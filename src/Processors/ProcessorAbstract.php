<?php

namespace SLI\Processors;

use SLI\Exceptions\SliLogicException;
use SLI\Translate;

/**
 * Interface ProcessorInterface
 * @package SLI\Processors
 */
abstract class ProcessorAbstract implements ProcessorInterface
{
    /**
     * @var null|\Closure
     */
    protected $missingTranslationCallback;

    /**
     * @var Translate
     */
    protected $translate;

    /**
     * @return Translate
     */
    public function getTranslate()
    {
        if (!$this->translate) {
            throw new SliLogicException('Uninitialized Translate object');
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

    /**
     * @inheritdoc
     */
    public function getMissingTranslationCallback()
    {
        return $this->missingTranslationCallback;
    }

    /**
     * @inheritdoc
     */
    public function setMissingTranslationCallback(\Closure $missingTranslationCallback)
    {
        $this->missingTranslationCallback = $missingTranslationCallback;

        return $this;
    }
}