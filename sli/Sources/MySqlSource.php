<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI\Sources;

use function implode;
use PDO;
use const DIRECTORY_SEPARATOR;
use function explode;
use function file_get_contents;
use function is_null;

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
            'mysql'
        ]);
    }

    /**
     * @param $fileName
     * @return bool
     */
    protected function executeSqlFile($fileName)
    {
        $sqlCommands = explode(';', trim(file_get_contents(
            $this->getMigrationDataDir()  .  DIRECTORY_SEPARATOR . $fileName
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
     * @param string $phrase
     * @param string $languageAlias
     * @return string
     */
    public function getTranslate($phrase, $languageAlias)
    {
        // TODO: Implement getTranslate() method.
    }

    /**
     * @param array  $phrases
     * @param string $languageAlias
     * @return array
     */
    public function getTranslates(array $phrases, $languageAlias)
    {
        // TODO: Implement getTranslates() method.
    }
}