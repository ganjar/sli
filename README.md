# SLI - Library for quick creating multi language web application

## Installation

```bash
$ composer require ganjar/sli
```

## Basic Usage

```php
<?php

use SLI\Language\Language;
use SLI\Processors\HardReplaceProcessor;
use SLI\Processors\HtmlAttributesProcessor;
use SLI\Processors\HtmlLinkProcessor;
use SLI\Processors\HtmlTagProcessor;
use SLI\SLI;
use SLI\SLIEvents;
use SLI\sources\PdoSource;

$sli = new SLI();

//Задаем источник переводов
$connection = new PDO("mysql:dbname=test;host=localhost", 'root', 'root');
$sliTranslateSource = new PdoSource($connection);
if (!$sliTranslateSource->isInstalled()) {
    $sliTranslateSource->install();
}
$sli->setSource($sliTranslateSource);

//Задаем обработчик выбора языка
//todo - решить как определять язык и возможность кастомизации (COOKIE, URL, etc)
//todo - вынести просто в SLIHelper определение языка на url
$language = new Language();
$language->setAlias('ua');
$sli->setLanguage($language);


//Добавляем обработчик буфера. В данном примере добавляем парсер html
$sliHtmlTagProcessor = new HtmlTagProcessor();
$sliHtmlTagProcessor->setIgnoreTags(['style', 'script']);
$sli->addProcessor($sliHtmlTagProcessor);

//Добавляем парсер html аттрибутов
$sliHtmlAttributesProcessor = new HtmlAttributesProcessor();
$sliHtmlAttributesProcessor->setAllowAttributes(['title', 'alt']);
$sli->addProcessor($sliHtmlAttributesProcessor);

//Добавляем обработчик буфера для замены в ссылках языковой приставки
$linkProcessor = new HtmlLinkProcessor();
$sli->addProcessor($linkProcessor);

//Добавляем жесткую замену текста
$hardReplaceProcessor = new HardReplaceProcessor();
$hardReplaceProcessor->addReplacement('привет', 'hello');
$sli->addProcessor($hardReplaceProcessor);

//Далее вы можете направить все уведомления об отсутствии переводов и тд в логер (PSR3)
//$sli->setLogger($monolog);

//events
$sli->on(\SLI\Event::EVENT_MISSING_TRANSLATION, function (SLI $sli, $phrase) {
    echo 'Untranslated:' . $phrase;
    echo 'Source:' . get_class($sli->getSource());
});
//TODO - add events
/*$sli->on(SLI::EVENT_BEFORE_BUFFERING, function(){});
$sli->on(SLI::EVENT_BEFORE_END_BUFFERING, function(){});
$sli->on(SLI::EVENT_BEFORE_TRANSLATE_BUFFER, function($buffer){});
$sli->on(SLI::EVENT_AFTER_TRANSLATE_BUFFER, function($buffer){});
$sli->on(SLI::EVENT_CATCH_NON_TRANSLATED_TEXT, function($text){});*/

$sli->getBuffer()->buffering(function(){
    echo '<b>Hello word</b>';
});
$sli->getBuffer()->start();

echo '<b>Hello word</b>';

$sli->getBuffer()->end();
$sli->getBuffer()->add('Hello to ;)');

//Быстрый перевод
echo $sli->translate('<b>Hello word</b>');
```



    /**
     * todo - Переделать на translate Pre & PostProcessors
     * Оработка текста перед
     * запросом в источник переводов
     * @var $text - string
     * @return string
     */
    protected function originalPreProcessor($text)
    {
        $text = preg_replace('!\d+!', '0', $text);
        $text = preg_replace('!\s+!s', ' ', $text);

        return trim($text);
    }

    /**
     * Обработка полученного перевода
     * @param string $original
     * @param string $translate
     * @return string
     */
    protected function translatePostProcessor($original, $translate)
    {
        //Заменяем измененные не переводимые части в значении перевода
        preg_match_all('#(?:[\d])+#u', $original, $symbols);
        preg_match_all('#(?:[\d])+#u', $translate, $tSymbols);

        $symbols = $symbols[0];
        $tSymbols = $tSymbols[0];

        if (!empty($symbols)) {

            $sPos = 0;
            foreach ($symbols as $symbolKey => $symbol) {

                $sPos = strpos($translate, $tSymbols[$symbolKey], $sPos);

                if ($sPos !== false) {
                    $translate = substr_replace($translate, $symbol, $sPos,
                        strlen($tSymbols[$symbolKey]));
                    $sPos += strlen($symbol);
                }
            }
        }

        return $translate;
    }