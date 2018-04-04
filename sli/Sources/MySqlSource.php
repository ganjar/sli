<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI\Sources;

use PDO;
use SLI\Exceptions\SliLogicException;
use SLI\Language\LanguageInterface;

/**
 * Class MySqlSource
 * @package SLI\Sources
 */
class MySqlSource extends PdoSourceAbstract
{
    const VERSION = 2.0;

    /**
     * @var array
     */
    protected $settings;


    /**
     * @return array
     */
    public function getSettings()
    {
        if (is_null($this->settings)) {
            $this->settings = $this->getPdo()->query(
                'select * from sli_setting'
            )->fetchAll(PDO::FETCH_ASSOC);
        }

        return $this->settings;
    }

    /**
     * @param string            $phrase
     * @param LanguageInterface $language
     * @return string
     */
    public function getTranslate($phrase, LanguageInterface $language)
    {
        foreach ($this->getTranslates([$phrase], $language) as $translate) {
            return $translate;
        }

        throw new SliLogicException('Empty list of translated phrases');
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

        //фразы, которых нет в БД
        $savePhrases = [];
        foreach ($phrases as $phrase) {
            if (!array_key_exists($phrase, $translates)) {
                $translates[$phrase] = null;
                $savePhrases[$phrase] = $phrase;
            }
        }

        //сохранение новых фраз в БД
        if ($savePhrases) {
            $this->insertPhrases($savePhrases);
        }

        return $translates;
    }

    /**
     * Сгенерировать ключи для БД
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
     * Сохранить файл оригиналов
     * @param $phrases
     * @return boolean
     */
    private function insertPhrases($phrases)
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

    //////todo - move to install source class
    /**
     * @return bool
     */
    protected function isInstalled()
    {
        return $this->getPdo()->query(
            'select COUNT(*) from information_schema.tables where table_schema=DATABASE() AND TABLE_NAME="sli_setting"'
        )->fetchColumn() ? true : false;
    }

    /**
     * @return bool
     */
    protected function install()
    {
        $sqlCommands = explode(';', trim(file_get_contents(
            __DIR__ .
            DIRECTORY_SEPARATOR .
            'data' .
            DIRECTORY_SEPARATOR .
            'mysql.sql'
        )));

        foreach ($sqlCommands as $sqlCommand) {
            if (!$sqlCommand) {
                continue;
            }
            $this->getPdo()->exec($sqlCommand);
        }

        return true;
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
     * @return bool
     */
    protected function isNeedUpdate()
    {
        //todo
        //return self::VERSION > $this->getSettings()['version'];
        return false;
    }

    //todo
    protected function update()
    {

    }
}