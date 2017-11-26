# SLI - Library for quick creating multi language web application

## Installation

Для начала определите каким образом планируете использование SLI
Автоматический перевод - в большинстве случаев удобней всего, так как
вам не прийдется все места обрамлять в вызов функции, вместо этого весь текст
между html будет вырезан (после отрисовки страницы) и заменен текстом выбранной языковой версии сайта.
Из плюсов - быстрая интеграция и скорость работы. Все переведенные фразы выбираются из БД одним запросом.
Недостатком данного метода является парсинг частей, которые не нуждаются в переводе (комментарии, технический контент и тд). Вы можете такие места
отметить стоп-тегами, внутренности которых парсер будет избегать.

Ручное указание какие фразы надо переводить. При подключении вы указываете данную стратегию работы. Вам надо будет
все места, в которых требуется перевод обрамить функцией SLI::t().

Самым правильным решением будет совмещение обеих стратегий.  По умолчанию мы парсим контент на перевод и лишь места, которые, допустим, 
отдаем через ajax/интегрируем в JS - принудительно переводим через SLI::t()

Для удобства работы с библиотекой - отдельным пакетом можете установить админку. Она добавляет отдельный класс, черзе который вы можете без
ручного указания настроек - интегрировать SLI. Либо - реализовать свою)

Для разных частей приложения можете использовать разные инстансы переводчика. $sliFrontend, $sliBackend, $sliTech.

Интеграция в Yii t('app')

```bash
$ composer require ganjar/sli
```

## Basic Usage

```php
<?php

use SLI\Api;

$sli = new SLI();
//Задаем источник переводов
$sliTranslateSource = new SliTranslatePdoSource($pdo);
if (!$sliTranslateSource->isInstalled()) {
    $sliTranslateSource->install();
}
$sli->setTranslateSource($sliTranslateSource);
//Добавляем обработчик буфера. В данном примере добавляем парсер html
$sliBufferProcessor = new SliHtmlBufferProcessor();
$sliBufferProcessor->setIgnoreTags(['style', 'script']);
$sli->setBufferProcessor($sliBufferProcessor);
//Настройки
$sliConfig = new SliConfig();
$sli->setConfig($sliConfig);

//Далее вы можете направить все уведомления библиотеки в логер
$sli->setLogger($monolog);

//events
$sli->on(SLI::EVENT_BEFORE_BUFFERING, function(){});
$sli->on(SLI::EVENT_BEFORE_END_BUFFERING, function(){});
$sli->on(SLI::EVENT_BEFORE_TRANSLATE_BUFFER, function($buffer){});
$sli->on(SLI::EVENT_AFTER_TRANSLATE_BUFFER, function($buffer){});
$sli->on(SLI::EVENT_CATCH_NON_TRANSLATED_TEXT, function($text){});

$sli->startBuffering(); //or$sli->buffering(function(){ echo '<b>Hello word</b>'; }); 

echo '<b>Hello word</b>';

$sli->endBuffering();
$sli->addBuffer('Hello to ;)');

//Сбрасываем буфер и отображаем перевод
echo $sli->getBufferTranslate();
$sli->clearBuffer();

//Быстрый перевод
echo $sli->translate('<b>Hello word</b>');
```