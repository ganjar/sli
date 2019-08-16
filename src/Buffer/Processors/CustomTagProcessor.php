<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI\Buffer\Processors;

use function preg_quote;
use function preg_replace;

/**
 * Class CustomTagProcessor
 * @package SLI\Buffer\Processors
 */
class CustomTagProcessor extends AbstractHtmlProcessor
{
    /**
     * @var string
     */
    protected $openTag;

    /**
     * @var string
     */
    protected $closeTag;

    /**
     * @var bool
     */
    protected $removeOpenCloseTags;

    /**
     * CustomTagProcessor constructor.
     * @param string $openTag
     * @param string $closeTag
     * @param bool   $removeOpenCloseTags
     */
    public function __construct($openTag, $closeTag, $removeOpenCloseTags = true)
    {
        $this->openTag = $openTag;
        $this->closeTag = $closeTag;
        $this->removeOpenCloseTags = $removeOpenCloseTags;
    }

    /**
     * Get RegEx for parse HTML and get all phrases for translate
     * @return string
     */
    public function getFindPhrasesRegex()
    {
        return '$' . preg_quote($this->openTag) . '(?<original>.+)' . preg_quote($this->closeTag) . '$Usi';
    }

    /**
     * @inheritdoc
     */
    public function process($buffer, $cleanBuffer)
    {
        $buffer = parent::process($buffer, $cleanBuffer);

        if ($this->removeOpenCloseTags) {
            $buffer = preg_replace($this->getFindPhrasesRegex(), '$1', $buffer);
        }

        return $buffer;
    }
}