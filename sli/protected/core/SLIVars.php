<?php
/**
 * SLIVars
 * Клас переменных
 * Суть переменных в том что бы заменить части JS или другого кода в соответствии с выбранным языком.
 * Замена происходит после полной локализации контента
 * @author Ganjar@ukr.net
 */

if (!defined('SLI_WORK_DIR')) { die('SLI_WORK_DIR is not defined');}
define('SLI_VARS_FILE_PATTERN', SLI_WORK_DIR.'/data/vars/%s.txt');
define('SLI_VARS_ORIGINAL_FILE', SLI_WORK_DIR.'/data/vars/original/original.dat');

class SLIVars {
	
	/**
	 * Данные перевода на текушую версию
	 * @var array
	 */	
	public static $_tData;
	
	/**
	 * Массив оригиналов
	 * @var array
	 */    
    public static $_originalData;	
    
    /**
     * Получить массив с данными для перевода
     * @var $lang - string
     * @return array
     */                   
    private static function getTranslateData($language)
    {                   
        if (is_null(self::$_tData)) {
            
            self::$_tData = array();
                                                                            
            //подключаем выбранную языковую версию
            $translate = self::getLanguageContent($language);
            
            if (!empty(self::$_originalData) && is_array(self::$_originalData) && !empty($translate) && is_array($translate)) {
                foreach ($translate as $key=>$value) {
                    if (empty(self::$_originalData[$key])) { continue;} 
                    self::$_tData[self::$_originalData[$key]] = $value;
                }
            }
            
        }         
        
        return self::$_tData;    
    }
    
    /**
     * Получить контент языковой версии
     * @var $lang - string
     * @return array
     */
    public static function getLanguageContent($lang)
    {
        $data = array();
        $tFile = sprintf(SLI_VARS_FILE_PATTERN, $lang);
        if (is_readable($tFile)) {
        	$data = @unserialize(file_get_contents($tFile));
        }    
        
        return $data ? $data : array();
    }
    
    /**
     * Получить контент оригинальной языковой версии
     * @return array
     */
    public static function getOriginalContent()
    {
        $data = array();
        if (is_readable(SLI_VARS_ORIGINAL_FILE)) { 
            $data = @unserialize(file_get_contents(SLI_VARS_ORIGINAL_FILE));
        }    
        
        return $data ? $data : array();
    }

    /**
     * Добавить значение в массив оригиналов на перевод
     * @var $lang - string
     * @return false or integer (insert id)
     */    
    private static function addOriginalText($text)
    {
        if (!self::$_originalData) {
            self::$_originalData = self::getOriginalContent();
        }
                                                       
        if (array_search($text, self::$_originalData)===false) {
            self::$_originalData[] = $text;
            return max(array_keys(self::$_originalData));
            
        } else {
            return false;
        }                                  
    }
    
    /**
     * Сохранить файл оригиналов
     * @return boolean
     */    
    private static function saveOriginalFile()
    {
        if (is_array(self::$_originalData)) {
            return file_put_contents(SLI_VARS_ORIGINAL_FILE, serialize(self::$_originalData));
        }          
        
        return false;                        
    }    
    
	/**
	 * Сохранить значение перевода
	 * @param integer $id
	 * @param mixed $language (если false - будет сохранен оригинал, иначе - перевод)
	 * @param unknown_type $text
	 * @return unknown
	 */
	public static function saveTranslate($id, $language, $text)
	{
		if ($language===false || in_array($language, SLITranslate::getLanguages())) {
            $id = (int)$id;
			$originalData = self::getOriginalContent();
			$countOriginalData = count($originalData);
			$translateData = array();
            
			//Сохраняем оригинал
			if ($language===false) {
				$tFile = SLI_VARS_ORIGINAL_FILE;
				$translateData = &$originalData;
			
			//Сохраняем перевод
			} else { 
			
				$tFile = sprintf(SLI_VARS_FILE_PATTERN, $language);
	            $translateData = self::getLanguageContent($language);
				$countTranslateData = count($translateData);
				
				if (empty($translateData) || $countTranslateData<$countOriginalData) {
	            	$countFill = $countOriginalData-$countTranslateData;
	                $translateData = array_merge($translateData, array_fill($countTranslateData, $countFill, ''));
	            }				
			}
			
            $translateData[$id] = stripcslashes($text);

            $result = file_put_contents($tFile, serialize($translateData));
		}
		
		return $result;
	}
	
	/**
	 * Сохранить переменную
	 * @param unknown_type $var
	 * @return boolean
	 */
	public static function saveVar($var)
	{
		$result = false;
		$id = self::addOriginalText($var['original']);
		
		if ($id!==false) {

			$result = self::saveOriginalFile();

			if ($result && !empty($var['translate']) && is_array($var['translate'])) {

    			foreach ($var['translate'] as $language => $text) {

    				//Сохраняем перевод
    				self::saveTranslate($id, $language, $text);
    			}
    		}
		}	
		
		return $result;
	}
	
	/**
	 * Удалить элемент
	 * @param mixed $id - integer или array из id элементов
	 * @return boolean
	 */
	public static function deleteItem($id)
	{
        $result = false;
        $data = array();
        
		//подгружаем все языковые версии
        $originalData = self::getOriginalContent();
        
        foreach (SLITranslate::getLanguages() as $lang) {
            $data[$lang] = self::getLanguageContent($lang);   
        }
        
        //удаляем айтемы
        if (!is_array($id)) {
            $id = array((int)$id);
        }
        
        //проверка на возможность записи в файл
        $isWritable = is_writable(SLI_VARS_ORIGINAL_FILE);
        if ($isWritable) {
            foreach ($data as $lang => $translateData) {
                if ($isWritable) { 
                    $isWritable = sprintf(SLI_VARS_FILE_PATTERN, $lang);
                } else {
                    break;
                }
            }            
        }
        
        if ($isWritable) {
            
            $result = true;
            
            foreach ($id as $value) {
                if (isset($originalData[$value])) { unset($originalData[$value]);}
                foreach ($data as $lang => $translateData) {
                    if (isset($translateData[$value])) { unset($data[$lang][$value]);}
                }
            }

            //сохраняем файлы
            file_put_contents(SLI_VARS_ORIGINAL_FILE, serialize($originalData));
            
            foreach ($data as $lang => $translateData) {
                file_put_contents(sprintf(SLI_VARS_FILE_PATTERN, $lang), serialize($translateData));
                
            }
                        
        }
        
        return $result;
	}
}