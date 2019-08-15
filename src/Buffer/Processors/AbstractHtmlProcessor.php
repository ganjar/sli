<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI\Buffer\Processors;

/**
 * Class AbstractHtmlProcessor
 * @package SLI\Buffer\Processors
 */
abstract class AbstractHtmlProcessor extends ProcessorAbstract
{
    /**
     * @param string $buffer
     * @param string $cleanBuffer
     * @return string
     * @throws \SLI\Exceptions\SliException
     */
    public function process($buffer, $cleanBuffer)
    {
        preg_match_all($this->getFindPhrasesRegex(), $cleanBuffer, $match);
        $originalData = [
            'match'    => $match[0],
            'original' => $match[1],
        ];

        $pos = 0;
        $translateData = $this->getTranslate()->translateAll($originalData['original']);

        foreach ($originalData['original'] AS $k => $original) {

            //find original phrase position
            $pos = strpos($buffer, $originalData['match'][$k], $pos);
            $pos = strpos($buffer, $original, $pos);

            //don't replace if we don't have translation
            if (empty($translateData[$original])) {
                continue;
            }

            $translate = htmlspecialchars($translateData[$original], ENT_QUOTES);

            //replace original to translate phrase
            $buffer = substr_replace($buffer, $translate, $pos, strlen($original));
        }

        return $buffer;
    }

    /**
     * Get RegEx for parse HTML and get all phrases for translate
     * @return string
     */
    abstract public function getFindPhrasesRegex();
}