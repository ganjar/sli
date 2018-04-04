<?php

use SLI\Language\Language;
use SLI\Processors\HardReplaceProcessor;
use SLI\Processors\HtmlAttributesProcessor;
use SLI\Processors\HtmlLinkProcessor;
use SLI\Processors\HtmlTagProcessor;
use SLI\SLI;
use SLI\Event;
use SLI\Sources\MySqlSource;
use SLI\sources\PdoSourceAbstract;
use SLI\Sources\YandexSource;

$sli = new SLI();

//Задаем источник переводов
$connection = new PDO("mysql:dbname=sli;host=localhost", 'root', 'hfccnjzybt');
$connection->exec('SET NAMES utf8');
$sliTranslateSource = new MySqlSource($connection);
//todo - use Install Source class
/*if (!$sliTranslateSource->isInstalled()) {
    $sliTranslateSource->install();
}*/
$sli->setSource($sliTranslateSource);
//$sli->setSource(new YandexSource('1'));

//Задаем язык
$language = new Language();
$language->setAlias('ua')->setIsOriginal(false);
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
$hardReplaceProcessor->addReplacement('Hello', 'привет');
$sli->addProcessor($hardReplaceProcessor);

//Далее вы можете направить все уведомления об отсутствии переводов и тд в логер (PSR3)
//$sli->setLogger($monolog);

//events
$events = new Event();
$sli->on(SLI::EVENT_MISSING_TRANSLATION, function (SLI $sli, $phrase) {
    echo 'Untranslated:' . $phrase;
    echo 'Source:' . get_class($sli->getSource());
});
//TODO - add events
/*$sli->on(SLIEvents::EVENT_BEFORE_BUFFERING, function(){});
$sli->on(SLIEvents::EVENT_BEFORE_END_BUFFERING, function(){});
$sli->on(SLIEvents::EVENT_BEFORE_TRANSLATE_BUFFER, function($buffer){});
$sli->on(SLIEvents::EVENT_AFTER_TRANSLATE_BUFFER, function($buffer){});
$sli->on(SLIEvents::EVENT_CATCH_NON_TRANSLATED_TEXT, function($text){});*/

/*$sli->getBuffer()->buffering(function(){
    echo '<b>Hello word</b>';
});

$sli->getBuffer()->start();

echo '<b>Hello word</b>';

//$sli->getBuffer()->end();
//$sli->getBuffer()->add('Hello to ;)');

//Быстрый перевод
//todo - придумать проверку того, что если метод вызывается в не закрытом buffer - надо не переводить повторно данную часть.
echo $sli->translate('<b>Hello word</b>');

$sli->getBuffer()->end();*/