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
class HtmlTagProcessor extends ProcessorAbstract
{
    protected $ignoreTags = [];

    const HTML_VAR_PATTERN = '<!--SLI::%s-->';

    /**
     * @return array
     */
    public function getIgnoreTags()
    {
        return $this->ignoreTags;
    }

    /**
     * @param array $ignoreTags
     * @return HtmlTagProcessor
     */
    public function setIgnoreTags($ignoreTags)
    {
        $this->ignoreTags = $ignoreTags;

        return $this;
    }

    /**
     * @param string $buffer
     * @return string
     */
    public function process($buffer)
    {
        //очистка контента от комментов и скриптов
        $cleanContent = $this->clearContent($buffer);

        //Ищем переводимый контент
        preg_match_all($this->getParseContentRegex(), $cleanContent, $match/*, PREG_OFFSET_CAPTURE*/);
        $originalData = [
            'match'    => $match[0],
            'original' => $match[1],
            'clean'    => [],
        ];

        foreach ($originalData['original'] AS $k => $v) {
            //чистим от переводов строк
            $originalData['clean'][$k] = $this->getCleanText($v);
        }

        $pos = 0;
        $translateData = $this->getSli()->translateAll($originalData['clean']);

        foreach ($originalData['original'] AS $k => $v) {
            $clean = $originalData['clean'][$k];
            if (empty($translateData[$clean])) {
                continue;
            }

            $translate = htmlspecialchars($translateData[$clean], ENT_QUOTES);
            //Проводим замену на перевод
            $pos = strpos($buffer, $originalData['match'][$k], $pos);
            $pos = strpos($buffer, $v, $pos);
            $buffer = substr_replace($buffer, $translate, $pos, strlen($v));
        }

        return $buffer;
    }

    /**
     * Получить текст для перевода,
     * очищенных от лишних пробелов и символов перевода строк
     * @var $text - string
     * @return string
     */
    public function getCleanText($text)
    {
        return trim(str_replace('  ', ' ', str_replace(["\r\n", "\r", "\n", '&nbsp;'], ' ', $text)));
    }

    /**
     * Получить регулярное выражение для переводимых частей
     * @return string
     */
    public function getParseContentRegex()
    {
        //test regex https://regex101.com/r/4rYAGP/4
        return '#
          (?:>|\A)
                (?:(?:&\#?[a-z0-9]{1,7};)|[^a-zа-яёїі<>])*
            		([a-zа-яёїі][^<]*[,.!?:;)}\]]?)(?![,.!?:;)}\]])
                (?:(?:&\#?[a-z0-9]{1,7};)|[^a-zа-яёїі<>])*
  		  (?:(?:(?!\\\)\\1)|(?:<|\Z))
        #Uuxsi';
    }

    /**
     * Получить контент без комментариев и скриптов для поиска заменяемых частей
     * @param $content
     * @return string
     */
    public function clearContent($content)
    {
        $ignoreTags = $this->getIgnoreTags();
        $regexp = [
            '(' . $this->ignoreStart() . '.*' . $this->ignoreEnd() . ')',
            '(<!--.*-->)',
        ];

        //Вырезаем игнорируеммые теги
        foreach ($ignoreTags as $tag) {
            $tag = preg_quote($tag);
            if ($tag) {
                $regexp[] = '(<' . $tag . '[\s>].*</' . $tag . '>)';
            }
        }

        $content = preg_replace('#' . implode('|', $regexp) . '#Usi', '', $content);

        return $content;
    }


    /**
     * Отобразить тег "игнорировать контент"
     * @return string
     * @internal param bool $print
     */
    public function ignoreStart()
    {
        return sprintf(self::HTML_VAR_PATTERN, 'ignore');
    }

    /**
     * Отобразить закрывающий тег "игнорировать контент"
     * @return string
     */
    public function ignoreEnd()
    {
        return sprintf(self::HTML_VAR_PATTERN, 'endIgnore');
    }
}