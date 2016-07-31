<?php

/**
 * SLICache
 * Клас кэширования
 * @author Ganjar@ukr.net
 */
class SLICache
{

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
		if (SLICore::getInstance()->cache() && $data = SLICore::getInstance()->cache()->get('SLI:' . $this->cacheId)) {

			$data = @unserialize($data);

			//Проверяем не устарели ли данные кэша
			if (!empty($data['controlData']) && $data['controlData'] != $controlData) {
				$data['value'] = null;
			}
		}

		return !empty($data['value']) ? $data['value'] : null;
	}

	/**
	 * Записать данные в кэш
	 * @param $data
	 * @param int|null $time
	 * @param null $controlData
	 */
	public function set($data, $time = null, $controlData = null)
	{
		if (SLICore::getInstance()->cache()) {
			SLICore::getInstance()->cache()->set('SLI:' . $this->cacheId, serialize(array(
					'time' => $time,
					'value' => $data,
					'controlData' => $controlData,
			)), false, $time);
		}
	}
}