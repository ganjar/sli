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
     * @return bool
     */
    public function isInstalled()
    {
        return $this->getPdo()->query(
            'select COUNT(*) from information_schema.tables where table_schema=DATABASE() AND TABLE_NAME="sli_setting"'
        )->fetchColumn() ? true : false;
    }

    /**
     * @return bool
     */
    public function install()
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

    public function getMigrationDataDir()
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
    public function isNeedUpdate()
    {
        return self::VERSION > $this->getSettings()['version'];
    }

    //todo
    public function update()
    {

    }


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
        //todo - заменить TEXT to BINARY
        $countPhrases = count($phrases);
        $dataQuery = $this->getPdo()->prepare("
                    SELECT o.`id`, o.`a`, o.`search`, o.`content` as `original`
                        " . ($language->getId() ? ', t.`content` as `translate`' : ',NULL as `translate`') . "
                        FROM `sli_original` AS `o`
                        FORCE INDEX(indexA)
                        " . ($language->getId() ? "LEFT JOIN `sli_translate` AS `t` ON(`o`.`id`=`t`.`original_id` AND `t`.`language_id`=:language_id)" : '') . "
                    WHERE " . (implode('OR',
                array_fill(0, $countPhrases,
                    '(`a`=? AND (`search` IS NULL OR `search`="" OR BINARY `search`=?))'))) . "
                    GROUP BY BINARY o.`search`, o.`a`
                    LIMIT $countPhrases
                ");
        if ($language->getId()) {
            $dataQuery->bindValue('language_id', $language->getId(), \PDO::PARAM_INT);
        }

        $paramKey = 0;
        $queryParams = $this->createQueryParams($phrases);
        foreach ($queryParams as $params) {
            foreach ($params as $param) {
                $paramKey++;
                $dataQuery->bindValue($paramKey, $param, is_null($param) ? \PDO::PARAM_NULL : \PDO::PARAM_STR);
            }
        }

        $dataQuery->execute();

        $translates = [];
        while ($translateRow = $dataQuery->fetch(PDO::FETCH_ASSOC)) {
            $translates[$translateRow['original']] = $translateRow['translate'];
        }

        //фразы, которых нет в БД
        foreach ($phrases as $phrase) {
            if (!isset($translates[$phrase])) {
                $translates[$phrase] = null;
            }
        }

        //todo - сохранение новых фраз в БД

        return $translates;
    }

    /**
     * Сгенерировать ключи для БД
     * @param array $searchKeys
     * @return array
     */
    protected function createQueryParams($searchKeys = [])
    {
        $keys = [];
        foreach ($searchKeys as $v) {
            $a      = mb_substr($v, 0, 64, 'utf8');
            $keys[] = [
                'a'      => $a,
                'search' => $a !== $v ? $v : null,
            ];
        }

        return $keys;
    }
}