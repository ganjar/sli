<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI\Processors;

/**
 * Class HtmlTagProcessor
 * @package SLI\Processors
 */
class HtmlTagProcessor extends AbstractHtmlProcessor
{
    /**
     * Get RegEx for parse HTML and get all phrases for translate
     * @return string
     */
    public function getFindPhrasesRegex()
    {
        //test regex https://regex101.com/r/4rYAGP/4
        return '#
          (?:>|\A)
                (?:(?:&nbsp;)|\s)*
            		([^\s<][^<]+)
                (?:(?:&nbsp;)|\s)*
  		  (?:<|\Z)
        #Uxusi';
    }
}