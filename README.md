# SLI - Library for quick creating multi language web application

## Installation

```bash
$ composer require ganjar/sli
```

## Basic Usage

```php
<?php

//Set translation source - MySQL
$connection = new PDO("mysql:dbname=test;host=localhost", 'root', 'root');
$sliTranslateSource = new \SLI\Translate\Sources\MySqlSource($connection);
if (!$sliTranslateSource->isInstalled()) {
    $sliTranslateSource->install();
}

//Set translation source - from file with || delimiter (original||translate)
//$fileSource = new \SLI\Translate\Sources\FileSource(__DIR__ . '/lng/ru.txt', '||');

//Set language
$language = new \SLI\Translate\Language\Language();
$language->setAlias('en');
$language->setIsOriginal(true);

//Make Translate instance
$translate = new \SLI\Translate\Translate(
    $language,
    $fileSource,
    new Event()
);
$translate->addOriginalProcessor(new TrimSpacesOriginalProcessor());

//BufferTranslate - class for parse and translate phrases in content
$bufferTranslate = new BufferTranslate($translate);

//PreProcessors - hide some content parts from buffer processors
$bufferTranslate->addPreProcessor(new IgnoreHtmlTagsPreProcessor(['style', 'script']));
$bufferTranslate->addPreProcessor(new HtmlCommentPreProcessor());
$bufferTranslate->addPreProcessor(new SliIgnoreTagPreProcessor());

//Add buffer processor for parse content in HTML tags
$bufferTranslate->addProcessor(new HtmlTagProcessor());

//Add buffer processor for parse phrases in custom tags
//$bufferTranslate->addProcessor(new CustomTagProcessor('[[', ']]'));

//Add processor for translate html attributes content
$sliHtmlAttributesProcessor = new HtmlAttributesProcessor();
$sliHtmlAttributesProcessor->setAllowAttributes(['title', 'alt', 'rel']);
$bufferTranslate->addProcessor($sliHtmlAttributesProcessor);

//Add processor for replace language in URLs
$bufferTranslate->addProcessor(new HtmlLinkProcessor());

$sli = new SLI();
$sli->setTranslate($translate);
$sli->setBufferTranslate($bufferTranslate);

//events
$sli->getEvent()->on(Event::EVENT_MISSING_TRANSLATION, function ($phrase, \SLI\Translate\Translate $translate) {
    //$translate->getSource()->saveToTranslateQueue($phrase);
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
echo $sli->getBufferTranslate()->translateAllAndReplaceInSource($resultHtml);*/


//Fast translate
//echo $sli->getTranslate()->translate('Hello word');


return $sli;