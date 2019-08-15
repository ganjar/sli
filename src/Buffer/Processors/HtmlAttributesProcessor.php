<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI\Buffer\Processors;

/**
 * Class HtmlAttributesProcessor
 * @package SLI\Buffer\Processors
 */
class HtmlAttributesProcessor extends AbstractHtmlProcessor
{
    protected $allowAttributes = [];

    /**
     * Allow html attributes translation
     * @return array
     */
    public function getAllowAttributes()
    {
        return $this->allowAttributes;
    }

    /**
     * @param array $allowAttributes
     */
    public function setAllowAttributes($allowAttributes)
    {
        $this->allowAttributes = $allowAttributes;
    }

    /**
     * Get RegEx for parse HTML and get all phrases for translate
     * @return string
     */
    public function getFindPhrasesRegex()
    {
        $allowAttributes = $this->getAllowAttributes();
        $regexp = [];

        foreach ($allowAttributes as $attr) {
            $attr = preg_quote($attr);
            $regexp[] = '(?:' . $attr . ')';
        }

        return '#
          (?:\s+(?:' . implode('|', $regexp) . ')\s*=\s*("|\'))
                (?:(?:&nbsp;)|\s)*
            		([^\s<][^<]+)
                (?:(?:&nbsp;)|\s)*
  		  (?:(?!\\\)\\1)
        #Uuxsi';
    }
}