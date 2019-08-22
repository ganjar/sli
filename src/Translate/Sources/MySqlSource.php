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
use SLI\Translate\Sources\Exceptions\MySqlSource\LanguageNotExistsException;

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
        $languageId = $this->getLanguageId($language);
        $dataQuery = $this->getPdo()->prepare(
            'SELECT o.`id`, o.`a`, o.`content` as `original`
                ' . ($languageId ? ', t.`content` as `translate`' : ',NULL as `translate`') . '
                FROM `' . $this->getTableOriginal() . '` AS `o`
                FORCE INDEX(indexA)
                ' . ($languageId ? 'LEFT JOIN `' . $this->getTableTranslate() . '` AS `t` ON(`o`.`id`=`t`.`original_id` AND `t`.`language_id`=:language_id)' : '') . '
            WHERE ' . (implode('OR', array_fill(0, $countPhrases, '(o.`a`=? AND BINARY o.`content`=?)'))) . '
            LIMIT ' . $countPhrases
        );
        if ($languageId) {
            $dataQuery->bindValue('language_id', $languageId, \PDO::PARAM_INT);
        }

        $paramKey = 0;
        foreach ($phrases as $phrase) {
            $queryParams = $this->createOriginalQueryParams($phrase);
            foreach ($queryParams as $param) {
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
     * Generate keys for find original phrase in database
     * @param string $phrase
     * @return array
     */
    protected function createOriginalQueryParams($phrase)
    {
        $a = mb_substr($phrase, 0, 64, 'utf8');

        return [
            'a'       => $a,
            'content' => $phrase,
        ];
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
     * @throws LanguageNotExistsException
     */
    public function saveTranslate(LanguageInterface $language, $original, $translate)
    {
        $originalId = $this->getOriginalId($original);
        if (!$originalId) {
            $originalId = $this->insertOriginal($original);
        }

        $languageId = $this->getLanguageId($language);
        if (!$languageId) {
            throw new LanguageNotExistsException('Language does not exists');
        }

        $updatePdo = $this->getPdo()->prepare("
                INSERT INTO `sli_translate` (`original_id`, `language_id`, `content`)
                VALUES (:id, :langId, :content)
                ON DUPLICATE KEY UPDATE `content`=:content
            ");
        $updatePdo->bindParam(':content', $translate, PDO::PARAM_STR);
        $updatePdo->bindParam(':id', $originalId, PDO::PARAM_INT);
        $updatePdo->bindParam(':langId', $languageId, PDO::PARAM_INT);
        $updatePdo->execute();
    }

    /**
     * @param LanguageInterface $language
     * @return int
     */
    public function getLanguageId(LanguageInterface $language)
    {
        $statement = $this->getPdo()->prepare("
                SELECT id FROM sli_language WHERE alias=:alias
            ");
        $statement->bindValue('alias', $language->getAlias());
        $statement->execute();
        $language = $statement->fetch(PDO::FETCH_COLUMN);

        return $language;
    }

    /**
     * @param string $original
     * @return mixed
     */
    public function getOriginalId($original)
    {
        $statement = $this->getPdo()->prepare("
                SELECT id FROM sli_original WHERE a=:a AND content=:content
            ");
        $queryParams = $this->createOriginalQueryParams($original);
        foreach ($queryParams as $queryKey => $queryParam) {
            $statement->bindValue($queryKey, $queryParam);
        }
        $statement->execute();
        $originalId = $statement->fetch(PDO::FETCH_COLUMN);

        return $originalId;
    }

    /**
     * @param string $original
     * @return string
     */
    public function insertOriginal($original)
    {
        $statement = $this->getPdo()->prepare(
            'INSERT INTO `' . $this->getTableOriginal() . '` (`a`, `content`) VALUES (:a, :content)'
        );

        $queryParams = $this->createOriginalQueryParams($original);
        foreach ($queryParams as $queryKey => $queryParam) {
            $statement->bindValue($queryKey, $queryParam);
        }

        $statement->execute();

        return $this->getPdo()->lastInsertId();
    }

    /**
     * Delete original and all translated phrases
     * @param string $original
     */
    public function delete($original)
    {
        $statement = $this->getPdo()->prepare("
                DELETE FROM `sli_original` WHERE a=:a AND content=:content
            ");
        $queryParams = $this->createOriginalQueryParams($original);
        foreach ($queryParams as $queryKey => $queryParam) {
            $statement->bindValue($queryKey, $queryParam);
        }
        $statement->execute();
    }
}