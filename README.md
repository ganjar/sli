# Site Language Injection (SLI)
**The easiest way to make your site multilingual**

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

//Set CSV files as a translation source. Files in dir /lng/ with "|" delimiter (original|translate)
//$fileSource = new \SLI\Translate\Sources\CsvFileSource(__DIR__ . '/lng/', '|', 'txt');

//Parse language
$languageAlias = false;
if (preg_match('#^/(?<language>\w{2})/#', $_SERVER['REQUEST_URI'], $parseUriMatches)) {
    $languageAlias = $parseUriMatches['language'];
}

//Set language
$language = new \SLI\Translate\Language\Language();
$language->setAlias($languageAlias);
$language->setIsOriginal($languageAlias == 'en' || !$languageAlias);

//Make Translate instance
$translate = new \SLI\Translate\Translate(
    $language,
    $fileSource,
    new \SLI\Event()
);
$translate->addOriginalProcessor(new \SLI\Translate\OriginalProcessors\TrimSpacesOriginalProcessor());

//BufferTranslate - class for parse and translate phrases in content
$bufferTranslate = new \SLI\Buffer\BufferTranslate($translate);

//PreProcessors - hide some content parts from buffer processors
$bufferTranslate->addPreProcessor(new \SLI\Buffer\PreProcessors\IgnoreHtmlTagsPreProcessor(['style', 'script']));
$bufferTranslate->addPreProcessor(new \SLI\Buffer\PreProcessors\HtmlCommentPreProcessor());
$bufferTranslate->addPreProcessor(new \SLI\Buffer\PreProcessors\SliIgnoreTagPreProcessor());

//Add buffer processor for parse content in HTML tags
$bufferTranslate->addProcessor(new \SLI\Buffer\Processors\HtmlTagProcessor());

//Add buffer processor for parse phrases in custom tags
//$bufferTranslate->addProcessor(new CustomTagProcessor('[[', ']]'));

//Add processor for translate html attributes content
$sliHtmlAttributesProcessor = new \SLI\Buffer\Processors\HtmlAttributesProcessor();
$sliHtmlAttributesProcessor->setAllowAttributes(['title', 'alt', 'rel']);
$bufferTranslate->addProcessor($sliHtmlAttributesProcessor);

//Add processor for replace language in URLs
$bufferTranslate->addProcessor(new \SLI\Buffer\Processors\HtmlLinkProcessor());

$sli = new \SLI\SLI();
$sli->setTranslate($translate);
$sli->setBufferTranslate($bufferTranslate);

//events
$sli->getEvent()->on(\SLI\Event::EVENT_MISSING_TRANSLATION, function ($phrase, \SLI\Translate\Translate $translate) {
    //$translate->getSource()->saveToTranslateQueue($phrase);
});

//Use buffers
$sli->iniSourceBuffering();

//start/end
$sli->getBuffer()->start();
echo '<b>Hello word</b>';
$sli->getBuffer()->end();

//Without translate because outside buffer
echo '<b>Without translate content</b>';

$sli->getBuffer()->start();
echo '<b>Translated content inside buffer</b>';
$sli->getBuffer()->end();

//simple add buffer
echo $sli->getBuffer()->add('<b>Hello word 3</b>');

//Buffering all content inside callback function
echo $sli->getBuffer()->buffering(function () {
    echo '<b>Hello word 4</b>';
});

//Quick translation of a specific phrase
echo $sli->getTranslate()->translate('Hello word');

//Buffering all next content
$sli->getBuffer()->start();

//Fast translate
//echo $sli->getTranslate()->translate('Hello word');
//Save translate
//$sli->getTranslate()->saveTranslate($language, 'Hello word', 'Привет мир');

return $sli;