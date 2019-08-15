<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI;

use SLI\Buffer\BufferTranslate;
use SLI\Exceptions\BufferTranslateNotDefinedException;
use SLI\Exceptions\TranslateNotDefinedException;
use SLI\Translate\Translate;

/**
 * Class SLI
 * @package SLI
 */
class SLI
{
    /**
     * @var Translate
     */
    protected $translate;

    /**
     * @var BufferTranslate
     */
    protected $bufferTranslate;

    /**
     * @param BufferTranslate $bufferTranslate
     * @return $this
     */
    public function setBufferTranslate(BufferTranslate $bufferTranslate)
    {
        $this->bufferTranslate = $bufferTranslate;

        return $this;
    }

    /**
     * @return BufferTranslate
     * @throws BufferTranslateNotDefinedException
     */
    public function getBufferTranslate()
    {
        if (!$this->bufferTranslate) {
            throw new BufferTranslateNotDefinedException('BufferTranslate is not defined');
        }

        return $this->bufferTranslate;
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
     * @return Translate
     * @throws TranslateNotDefinedException
     */
    public function getTranslate()
    {
        if (!$this->translate) {
            throw new TranslateNotDefinedException('Translate is not defined');
        }

        return $this->translate;
    }
}