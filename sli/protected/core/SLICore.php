<?php
/**
 * SLICore
 * Ядро системы
 * @author Ganjar@ukr.net
 */
if (!defined('SLI_WORK_DIR')) { die('SLI_WORK_DIR is not defined');}

class SLICore {

    /**
     * Ядро системы
     * @var SLICore
     */
    protected static $__core;

    /**
     * Парметры конфига
     * @var object
     */
    protected $__config;

    /**
     * База данных
     * @var PDO
     */
    protected $__db;

    /**
     * Кэширование
     * @var Memcache
     */
    protected $__cache;

    /**
     * @return SLICore
     */
    public static function getInstance()
    {
        if (is_null(self::$__core)) {
            self::$__core = new SLICore();
            self::$__core->__config = (object)include SLI_WORK_DIR.'/config/config.php';
            self::$__core->dbInit();
            self::$__core->cacheInit();
        }

        return self::$__core;
    }

    protected function dbInit()
    {
        if ($this->config()->db['host'] && $this->config()->db['username'] && $this->config()->db['database']) {
            $this->__db = new PDO(
                'mysql:host='.$this->config()->db['host'].';dbname='.$this->config()->db['database'].';charset='.$this->config()->db['encoding'].';',
                $this->config()->db['username'],
                $this->config()->db['password'],
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".$this->config()->db['encoding'])
            );
        } else {
            $this->__db = false;
        }
    }

    protected function cacheInit()
    {
        if (SLISettings::getInstance()->getVar('cacheStatus')) {
            $this->__cache = new Memcache();
            $this->__cache->connect($this->config()->memcache['host'], $this->config()->memcache['port']) or $this->__cache = false;
        }
    }

    /**
     * Получить настройки системы
     * @return PDO
     */
    public function db()
    {
        return $this->__db;
    }

    /**
     * Получить настройки системы
     * @return Memcache
     */
    public function cache()
    {
        return $this->__cache;
    }

    /**
     * Получить настройки системы
     * @return array
     */
    public function config()
    {
        return $this->__config;
    }

    /**
     * Получить настройки системы
     * @param $name
     * @return array
     */
    public function configParam($name)
    {
        return isset($this->__config->$name) ? $this->__config->$name : null;
    }
}