<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI\Processors;

/**
 * You may use this processor if you want to store information about language in URL.
 * Processor replace all links (/about/) to links with current language (/ru/about/)
 * Class HtmlLinkProcessor
 * @package SLI\Processors
 */
class HtmlLinkProcessor extends ProcessorAbstract
{
    /**
     * @param string $buffer
     * @return string
     * @throws \SLI\Exceptions\SliException
     */
    public function process($buffer)
    {
        //Заменяем ссылки на картинки и файлы что бы не локализировать
        //todo - set excepted extensions (or set allow extension!)
        $buffer = preg_replace('#<a([^>]*)href=("|\')([^>]+\.(?:jpg|png|gif|pdf|jpeg|zip|rar|tar|ico|mp3))#Usi',
            '<a$1href_sli_file=$2$3', $buffer);

        //Локализируем
        //todo - set allowToTranslateURLs
        //$allow = self::getAllowPregString();
        $allow = false;
        $language = $this->getTranslate()->getLanguage()->getAlias();
        $host = preg_quote($_SERVER['HTTP_HOST']);
        $buffer = preg_replace('#<(a|base)(?! %)([^>]*)href=("|\')((/)(?!' . $language . '/)|(https?://' . $host . ')(?!/' . $language . '/))(' . ($allow ? $allow : '[^>]*') . ')(?!\\\)\\3(.*)>#Usi',
            '<$1$2href=$3$6/' . $language . '$5$7$3$8>', $buffer);
        $buffer = preg_replace('#<form([^>]*)action=("|\')((/)(?!' . $language . '/)|(https?://' . $host . ')(?!/' . $language . '/))(' . ($allow ? $allow : '[^>]*') . ')(?!\\\)\\2(.*)>#Usi',
            '<form$1action=$2$5/' . $language . '$4$6$2$7>', $buffer);
        $buffer = preg_replace('#(?:document\.)?location\.href\s*=\s*("|\')((/)(?!' . $language . '/)|(https?://' . $host . ')(?!/' . $language . '/))(' . ($allow ? $allow : '.*') . ')(?!\\\)\\1#Ui',
            'location.href=$1$4/' . $language . '$3$5$1', $buffer);
        $buffer = str_replace('<a %', '<a ', $buffer);


        $buffer = str_replace('href_sli_file=', 'href=', $buffer);
        $buffer = preg_replace('#href=("|\')([^>]+/' . $language . ')\\1#Usi', 'href=$1$2/$1', $buffer);

        return $buffer;
    }

    /**
     * Получить локализованный адрес (только полные адреса начинающиеся на / или https?://)
     * @var string
     * @return string
     * @throws \SLI\Exceptions\SliException
     */
    public function getLocalizedUrl($url)
    {
        if (!$this->getTranslate()->getLanguage()->getIsOriginal()
            && is_string($url)
            && ($url[0] == '/' || strpos($url, 'http://') !== false || strpos($url, 'https://') !== false)
        ) {
            //todo
            //$allow = self::getAllowPregString();
            $language = $this->getTranslate()->getLanguage()->getAlias();
            $allow = false;
            $url = preg_replace('#^((?:(/)(?!' . $language . '/))|(?:(https?://' . preg_quote($_SERVER['HTTP_HOST']) . ')(?!/' . $language . '/)))(' . ($allow ? $allow : '.*') . ')$#Ui',
                '$3/' . $language . '$2$4', $url);
        }

        return $url;
    }
}