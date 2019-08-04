<?php

namespace SLI\Sources;

use Exception;
use function explode;
use function file;
use function file_exists;
use function is_null;
use function is_readable;
use SLI\Language\LanguageInterface;

class FileSource extends SourceAbstract
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $allTranslates;

    /**
     * @var string
     */
    protected $delimiter;

    /**
     * FileSource constructor.
     * @param string $path
     * @param string $delimiter
     */
    public function __construct($path, $delimiter)
    {
        $this->path = $path;
        $this->delimiter = $delimiter;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string            $phrase
     * @param LanguageInterface $language
     * @return string
     */
    public function getTranslate($phrase, LanguageInterface $language)
    {
        if (is_null($this->allTranslates)) {
            $this->allTranslates = $this->parseFile();
        }

        return isset($this->allTranslates[$phrase]) ? $this->allTranslates[$phrase] : '';
    }

    /**
     * @return array
     */
    protected function parseFile()
    {
        $translates = [];
        if (!file_exists($this->getPath())) {
            //todo - custom exception
            throw new Exception('File source for translates not found');
        }
        if (!is_readable($this->getPath())) {
            //todo - custom exception
            throw new Exception('Cannot read file');
        }

        $lines = file($this->getPath());
        foreach ($lines as $line) {
            $lineParts = explode($this->delimiter, $line);
            $translates[$lineParts[0]] = $lineParts[1];
        }

        return $translates;
    }
}