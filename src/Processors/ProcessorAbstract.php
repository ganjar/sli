<?php

namespace SLI\Processors;

use SLI\Exceptions\SliLogicException;
use SLI\SLI;

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
     * @var SLI
     */
    protected $sli;

    /**
     * @return SLI
     */
    public function getSli()
    {
        if (!$this->sli) {
            throw new SliLogicException('Uninitialized SLI object');
        }
        return $this->sli;
    }

    /**
     * @param SLI $sli
     * @return $this
     */
    public function setSli(SLI $sli)
    {
        $this->sli = $sli;

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