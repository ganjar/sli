<?php

namespace SLI\Translate\Sources;

use SLI\Translate\Language\LanguageInterface;
use SLI\Translate\Sources\Exceptions\CsvFileSource\DirectoryNotFoundException;
use SLI\Translate\Sources\Exceptions\CsvFileSource\FileNotWritableException;
use SLI\Translate\Sources\Exceptions\CsvFileSource\FileReadPermissionsException;
use SLI\Translate\Sources\Exceptions\CsvFileSource\UnsupportedLanguageAliasException;

/**
 * Source for simple translation storage. Directory with text files.
 * File names - must be in format language_alias.file_extension.
 * Language alias - allowed only word symbols and "-_"
 * Content in files - first original and after delimiter - translate.
 * Class FileSource
 * @package SLI\Translate\Sources
 */
class CsvFileSource extends SourceAbstract
{
    /**
     * @var string
     */
    protected $directoryPath;

    /**
     * CSV delimiter - only one symbol
     * @var string
     */
    protected $delimiter;
    /**
     * @var string
     */
    protected $filesExtension = 'txt';

    /**
     * @var array
     */
    protected $allTranslates;

    /**
     * FileSource constructor.
     * @param string $directoryPath - Directory with source files
     * @param string $delimiter - CSV delimiter may be only one symbol
     * @param string $filesExtension
     */
    public function __construct($directoryPath, $delimiter, $filesExtension)
    {
        $this->directoryPath = $directoryPath;
        $this->delimiter = $delimiter;
        $this->filesExtension = $filesExtension;
    }

    /**
     * @return string
     */
    public function getDirectoryPath()
    {
        return $this->directoryPath;
    }

    /**
     * @param $languageAlias
     * @return string
     * @throws UnsupportedLanguageAliasException
     */
    public function getLanguageFilePath($languageAlias)
    {
        if (preg_match('#[^\w_\-]#uis', $languageAlias)) {
            throw new UnsupportedLanguageAliasException('Unsupported language alias');
        }

        return rtrim($this->getDirectoryPath(), '/\\') . DIRECTORY_SEPARATOR . $languageAlias . '.' . $this->filesExtension;
    }

    /**
     * @param string            $phrase
     * @param LanguageInterface $language
     * @return string
     * @throws FileReadPermissionsException
     * @throws DirectoryNotFoundException
     * @throws UnsupportedLanguageAliasException
     */
    public function getTranslate($phrase, LanguageInterface $language)
    {
        if (is_null($this->allTranslates[$language->getAlias()])) {
            $this->allTranslates[$language->getAlias()] = $this->parseLanguageFile($language->getAlias());
        }

        if (isset($this->allTranslates[$language->getAlias()][$phrase])) {
            return $this->allTranslates[$language->getAlias()][$phrase];
        }

        return '';
    }

    /**
     * @param string $languageAlias
     * @return array
     * @throws FileReadPermissionsException
     * @throws DirectoryNotFoundException
     * @throws UnsupportedLanguageAliasException
     */
    protected function parseLanguageFile($languageAlias)
    {
        $translates = [];

        if (!file_exists($this->getDirectoryPath()) || !is_dir($this->getDirectoryPath())) {
            throw new DirectoryNotFoundException('Directory not found ' . $this->getDirectoryPath());
        }

        $languageFile = $this->getLanguageFilePath($languageAlias);

        if (file_exists($languageFile)) {
            if (!is_readable($languageFile)) {
                throw new FileReadPermissionsException('Cannot read file ' . $languageFile);
            }

            $fileResource = fopen($languageFile, 'r');
            while ( ($data = fgetcsv($fileResource, 0, $this->delimiter) ) !== false ) {
                $translates[$data[0]] = isset($data[1]) ? $data[1] : '';
            }
            fclose($fileResource);
        }

        return $translates;
    }

    /**
     * @param string $languageAlias
     * @param array  $translatesData - [original => translate]
     * @throws UnsupportedLanguageAliasException
     * @throws FileNotWritableException
     */
    protected function saveLanguageFile($languageAlias, $translatesData)
    {
        $filePath = $this->getLanguageFilePath($languageAlias);
        if (!is_writable($filePath)) {
            throw new FileNotWritableException('File is not writable ' . $this->getDirectoryPath());
        }

        $fileResource = fopen($filePath, 'w');

        foreach ($translatesData as $original => $translate) {
            fputcsv($fileResource, [$original, $translate], $this->delimiter);
        }

        fclose($fileResource);
    }

    /**
     * @param LanguageInterface $language
     * @param string            $original
     * @param string            $translate
     * @throws DirectoryNotFoundException
     * @throws FileReadPermissionsException
     * @throws UnsupportedLanguageAliasException
     * @throws FileNotWritableException
     */
    public function saveTranslate(LanguageInterface $language, $original, $translate)
    {
        $translates = $this->parseLanguageFile($language->getAlias());
        $translates[$original] = $translate;
        $this->saveLanguageFile($language->getAlias(), $translates);
    }
}