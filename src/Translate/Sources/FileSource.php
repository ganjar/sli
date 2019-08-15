<?php

namespace SLI\Sources;

use function explode;
use function file;
use function file_exists;
use function is_null;
use function is_readable;
use SLI\Language\LanguageInterface;
use SLI\Sources\Exceptions\FileNotFoundException;
use SLI\Sources\Exceptions\FileReadPermissionsException;

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
     * @throws FileNotFoundException
     * @throws FileReadPermissionsException
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
     * @throws FileNotFoundException
     * @throws FileReadPermissionsException
     */
    protected function parseFile()
    {
        $translates = [];

        if (!file_exists($this->getPath())) {
            throw new FileNotFoundException('File source for translates not found' . $this->getPath());
        }

        if (!is_readable($this->getPath())) {
            throw new FileReadPermissionsException('Cannot read file ' . $this->getPath());
        }

        $lines = file($this->getPath());
        foreach ($lines as $line) {
            $lineParts = explode($this->delimiter, $line);
            $translates[$lineParts[0]] = $lineParts[1];
        }

        return $translates;
    }
}