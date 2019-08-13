<?php

namespace SLI\Processors;

use SLI\Exceptions\SliException;
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
     * @throws SliException
     */
    public function getTranslate()
    {
        if (!$this->translate) {
            throw new SliException('Uninitialized Translate object');
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