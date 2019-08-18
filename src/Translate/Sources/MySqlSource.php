<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI\Translate\Sources;

use PDO;
use SLI\Exceptions\SliException;
use SLI\Translate\Language\LanguageInterface;

/**
 * Class MySqlSource
 * @package SLI\Sources
 */
class MySqlSource extends PdoSourceAbstract
{
    /**
     * @param string            $phrase
     * @param LanguageInterface $language
     * @return string
     * @throws SliException
     */
    public function getTranslate($phrase, LanguageInterface $language)
    {
        foreach ($this->getTranslates([$phrase], $language) as $translate) {
            return $translate;
        }

        throw new SliException('Empty list of translated phrases');
    }

    /**
     * @param array             $phrases
     * @param LanguageInterface $language
     * @return array
     */
    public function getTranslates(array $phrases, LanguageInterface $language)
    {
        $countPhrases = count($phrases);
        $dataQuery = $this->getPdo()->prepare(
'SELECT o.`id`, o.`a`, o.`content` as `original`
                ' . ($language->getId() ? ', t.`content` as `translate`' : ',NULL as `translate`') . '
                FROM `' . $this->getTableOriginal() . '` AS `o`
                FORCE INDEX(indexA)
                ' . ($language->getId() ? 'LEFT JOIN `' . $this->getTableTranslate() . '` AS `t` ON(`o`.`id`=`t`.`original_id` AND `t`.`language_id`=:language_id)' : '') . '
            WHERE ' . (implode('OR', array_fill(0, $countPhrases, '(o.`a`=? AND BINARY o.`content`=?)'))) . '
            LIMIT ' . $countPhrases
        );
        if ($language->getId()) {
            $dataQuery->bindValue('language_id', $language->getId(), \PDO::PARAM_INT);
        }

        $paramKey = 0;
        $queryParams = $this->createQueryParams($phrases);
        foreach ($queryParams as $params) {
            foreach ($params as $param) {
                $paramKey++;
                $dataQuery->bindValue($paramKey, $param, \PDO::PARAM_STR);
            }
        }

        $dataQuery->execute();

        $translates = [];
        while ($translateRow = $dataQuery->fetch(PDO::FETCH_ASSOC)) {
            $translates[$translateRow['original']] = $translateRow['translate'];
        }

        //phrases that aren't in the database
        foreach ($phrases as $phrase) {
            if (!array_key_exists($phrase, $translates)) {
                $translates[$phrase] = '';
            }
        }

        return $translates;
    }

    /**
     * Generate keys for find in database
     * @param array $phrases
     * @return array
     */
    protected function createQueryParams($phrases = [])
    {
        $keys = [];
        foreach ($phrases as $phrase) {
            $a = mb_substr($phrase, 0, 64, 'utf8');
            $keys[] = [
                'a'       => $a,
                'content' => $phrase,
            ];
        }

        return $keys;
    }

    /**
     * @param $phrases
     * @return boolean
     */
    public function insertPhrases($phrases)
    {
        if (!$phrases) {
            return false;
        }

        $countPhrases = count($phrases);
        $queryParams = $this->createQueryParams($phrases);

        $dataQuery = $this->getPdo()->prepare(
            'INSERT INTO `' . $this->getTableOriginal() . '` (`a`, `content`) VALUES
            ' . (implode(', ', array_fill(0, $countPhrases, '(?, ?)')))
        );

        $paramKey = 0;
        foreach ($queryParams as $params) {
            foreach ($params as $param) {
                $paramKey++;
                $dataQuery->bindValue($paramKey, $param, PDO::PARAM_STR);
            }
        }

        $dataQuery->execute();

        return true;
    }

    /**
     * @return array
     */
    public function getTables()
    {
        return [
            'original'  => 'sli_original',
            'translate' => 'sli_translate',
        ];
    }

    /**
     * @return string
     */
    public function getTableOriginal()
    {
        return $this->getTables()['original'];
    }

    /**
     * @return string
     */
    public function getTableTranslate()
    {
        return $this->getTables()['translate'];
    }

    /**
     * @return bool
     */
    public function isInstalled()
    {
        return $this->getPdo()->query(
            'select COUNT(*) from information_schema.tables where table_schema=DATABASE() AND TABLE_NAME="sli_original"'
        )->fetchColumn() ? true : false;
    }

    /**
     * @return bool
     */
    public function install()
    {
        return $this->executeSqlFile('install.sql');
    }

    protected function getMigrationDataDir()
    {
        return implode(DIRECTORY_SEPARATOR, [
            __DIR__,
            'data',
            'mysql',
        ]);
    }

    /**
     * @param $fileName
     * @return bool
     */
    protected function executeSqlFile($fileName)
    {
        $sqlCommands = explode(';', trim(file_get_contents(
            $this->getMigrationDataDir() . DIRECTORY_SEPARATOR . $fileName
        )));

        foreach ($sqlCommands as $sqlCommand) {
            if (!$sqlCommand) {
                continue;
            }
            $this->getPdo()->exec($sqlCommand);
        }

        return true;
    }

    /**
     * @param LanguageInterface $language
     * @param string            $original
     * @param string            $translate
     */
    public function saveTranslate(LanguageInterface $language, $original, $translate)
    {
        // TODO: Implement saveTranslate() method.
    }
}