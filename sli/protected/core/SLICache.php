<?php
/**
 * SLICache
 * Клас кэширования
 * @author Ganjar@ukr.net
 */

class SLICache {
	
	/**
	 * Объекты кэша
	 * @var array
	 */
	protected static $_objects = array();
	
	/**
	 * Идентификационный номер кэша
	 * @var string
	 */
	public $cacheId;
	
	/**
	 * Данные кэша
	 * @var mixed
	 */
	protected $_data;

    /**
     * Инициализация
     * @param $cacheId
     * @return SLICache
     */
    public static function getInstance($cacheId)
	{
		if (!isset(self::$_objects[$cacheId])) {
			self::$_objects[$cacheId] = new SLICache();
			self::$_objects[$cacheId]->cacheId = $cacheId;
		}
		
		return self::$_objects[$cacheId];
	}

    /**
     * Получить данные кэша
     * @param mixed $controlData
     * @return mixed
     */
    public function get($controlData = null)
	{
		if (SLICore::getInstance()->cache() && $data = SLICore::getInstance()->cache()->get('SLI:'.$this->cacheId)) {

            $this->_data = unserialize($data);
            unset($data);

            //Проверяем не устарели ли данные кэша
            if (
                (!empty($this->_data['controlData']) && $this->_data['controlData']!=$controlData)
            ) {
                $this->_data['value'] = null;
            }
		}
		
		return !empty($this->_data['value']) ? $this->_data['value'] : null;
	}
	
	/**
	 * Записать данные в кэш
	 */
	public function set($data, $time = null, $controlData = null)
	{
		$this->_data = array(
			'time' 		    => $time,
			'value' 		=> $data,
			'controlData' 	=> $controlData,
		);
	}

	/**
	 * Сохранение данных в файл
	 */
	public function __destruct()
	{
        if (SLICore::getInstance()->cache()) {

            $time = !empty($this->_data['time']) ? $this->_data['time'] : 0;
            unset($this->_data['time']);
            SLICore::getInstance()->cache()->set('SLI:'.$this->cacheId, serialize($this->_data), false, $time);
        }
	}
}