<?php

use SLI\Configurator;
use SLI\Language\Language;
use SLI\PreProcessors\HtmlCommentPreProcessor;
use SLI\PreProcessors\IgnoreHtmlTagsPreProcessor;
use SLI\PreProcessors\SliIgnoreTagPreProcessor;
use SLI\Processors\HardReplaceProcessor;
use SLI\Processors\HtmlAttributesProcessor;
use SLI\Processors\HtmlLinkProcessor;
use SLI\Processors\HtmlTagProcessor;
use SLI\SLI;
use SLI\Sources\FileSource;
use SLI\Sources\MySqlSource;

$configurator = new Configurator();
$sli = new SLI($configurator);

//Задаем источник переводов

//From MySQL
/*$connection = new PDO("mysql:dbname=sli;host=localhost;charset=utf8", 'root', 'root');
$sliTranslateSource = new MySqlSource($connection);
if (!$sliTranslateSource->isInstalled()) {
    $sliTranslateSource->install();
}
$configurator->setSource($sliTranslateSource);*/

//From file
$fileSource = new FileSource(__DIR__ . '/translates/uk.txt', '||');
$configurator->setSource($fileSource);

//Задаем обработчик выбора языка
//todo - решить как определять язык и возможность кастомизации (COOKIE, URL, etc)
//todo - вынести просто в SLIHelper определение языка на url
$language = new Language();
$language->setAlias('uk');
$configurator->setLanguage($language);

//PreProcessors
$configurator->addPreProcessor(new IgnoreHtmlTagsPreProcessor(['style', 'script']));
$configurator->addPreProcessor(new HtmlCommentPreProcessor());
$configurator->addPreProcessor(new SliIgnoreTagPreProcessor());

//Добавляем обработчик буфера. В данном примере добавляем парсер html
$sliHtmlTagProcessor = new HtmlTagProcessor();
$configurator->addProcessor($sliHtmlTagProcessor);

//Добавляем парсер html аттрибутов
$sliHtmlAttributesProcessor = new HtmlAttributesProcessor();
$sliHtmlAttributesProcessor->setAllowAttributes(['title', 'alt']);
$configurator->addProcessor($sliHtmlAttributesProcessor);

//Добавляем обработчик буфера для замены в ссылках языковой приставки
$linkProcessor = new HtmlLinkProcessor();
$configurator->addProcessor($linkProcessor);

//Добавляем жесткую замену текста
$hardReplaceProcessor = new HardReplaceProcessor();
$hardReplaceProcessor->addReplacement('Currency pairs', 'Валютные пары:');
$configurator->addProcessor($hardReplaceProcessor);

//Далее вы можете направить все уведомления об отсутствии переводов и тд в логер (PSR3)
//$configurator->setLogger($monolog);

//events
$configurator->getEvent()->on(SLI::EVENT_MISSING_TRANSLATION, function ($phrase, \SLI\Translate $translate) {
    echo 'Untranslated:' . $phrase . '; Source:' . get_class($translate->getSource());
});

//Use buffers

/*ob_start();

echo $sli->getBuffer()->buffering(function(){
    echo '<b>Hello word</b>';
});

//start/end
$sli->getBuffer()->start();
echo '<b>Hello word</b>';
$sli->getBuffer()->end();

//simple add
echo $sli->getBuffer()->add('Hello to ;)');

$resultHtml = ob_get_clean();

//replace all buffers id in result html
echo $sli->processAllBuffers($resultHtml);*/


//Fast translate
//echo $sli->getTranslate()->translate('Hello word');

return $sli;