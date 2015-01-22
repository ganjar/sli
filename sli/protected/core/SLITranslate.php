<?php
/**
 * SLITranslate
 * Клас переводчика
 * @author Ganjar@ukr.net
 */

if (!defined('SLI_WORK_DIR')) { die('SLI_WORK_DIR is not defined');}

class SLITranslate {

    const VAR_PATTERN = '{SLI:%s}';
    const VAR_HTTP_PATTERN = '<!--SLI::%s-->';

	/**
	 * Текущая версия сайта
	 * @var string
	 */
	public static $language;
	
	/**
	 * Данные перевода на текушую версию
	 * @var array
	 */	
	public static $_tData;
	
	/**
	 * Массив оригиналов
	 * @var array
	 */    
    public static $_originalData = array();

	/**
	 * Автоперевод
	 * @var boolean
	 */
    private static $_autoTranslate = true;
    
    /**
	 * Статус запуска автоперевода
	 * @var boolean
	 */    
    private static $_runAutoTranslate = false;

    /**
     * Список разрешенных адресов
     * @var array|null
     */
    protected static $_allowUrls;

    /**
     * Список разрешенных адресов для регулярного выражения
     * @var string|null
     */
    protected static $_allowPregUrls;

    /**
     * Callback функия, вызываемая после отработки клиента
     * @var string
     * @return string     
     */               
    public static function autoInit($content)
    {
    	//Если нужно произвести автоперевод
        if (self::$_autoTranslate) {
        
            //если нужно отобразить время затраченное на перевод
            if (SLISettings::getInstance()->getVar('showRuntime')) {
                $startTime = microtime(true);
            }

        	//Устанавливаем значение автоперевода
        	self::setAutoTranslate(false);
        	self::$_runAutoTranslate = true;
            
            $content = self::init($content);

            if (SLISettings::getInstance()->getVar('showRuntime')) {
                $runTime = microtime(true) - $startTime;
                $content .= SLIApi::ignoreStart(false).'<div class="sli-runtime">SLI Runtime: '.$runTime.'</div>'.SLIApi::ignoreEnd(false);
            }

            //Сохраняем переводы
            self::saveOriginalData();
        }
        
        return $content;
    }

    /**
     * Частичный перевод текста (переводимый текст нужно помещать в игнорируемые теги)
     * @param string $content
     * @param string $language
     * @param bool   $autotranslate
     * @return string
     */
    public static function t($content, $language = '', $autotranslate = null)
    {
    	return self::init($content, $language, $autotranslate);
    }
    
    /**
     * Инициализация переводчика
     * @var string $content
     * @var string $language
     * @var string $autotranslate - автоперевод после отдачи контента
     * @return string     
     */
    public static function init($content, $language = '', $autotranslate = null)
    {
        //Устанавливаем значение автоперевода
        if (is_bool($autotranslate)) {
            self::setAutoTranslate($autotranslate);
        }

        //Проверяем инициализацию языка
        if (!$language) {
            self::$language = defined('SLI_CURRENT_LANGUAGE') ? SLI_CURRENT_LANGUAGE : self::getCurrentLanguage();
        } else {
        	self::$language = $language;
        }

        if (self::$language && !self::isHaveIgnoreContent($content)) {
        	//Локализируем ссылки
            $content = self::getLocalizedLinks($content);
            //Локализируем контент если это принудительный перевод или первый и адрес не игнорируется
            if ((!self::$_runAutoTranslate || !self::isIgnoreUrl($_SERVER['REQUEST_URI']))) {
                $content = self::getLocalizedContent($content);
            }
        } else {
        	//Ищем данные для перевода если адрес не игнорируется
            if (!self::isIgnoreUrl($_SERVER['REQUEST_URI'])) {
                self::searchNewTranslationData($content);   
            }
        }

        //Очистить контент от переменных переводчика (если не сканер SLI)
        if (!SLIApi::isScannerBot()) {
            $content = self::cleanSliVars($content);
        }

    	return $content;
    }
    
    /**
     * Установить значение автоперевода
     */
    public static function setAutoTranslate($status)
    {
        self::$_autoTranslate = $status;
    }

    /**
     * Получить локализованные ссылки
     * @var string
     * @return string     
     */    
    public static function getLocalizedLinks($content)
    {
        //Заменяем ссылки на картинки и файлы что бы не локализировать
        $content = preg_replace('#<a([^>]*)href=("|\')([^>]+\.(?:jpg|png|gif|pdf|jpeg|zip|rar|tar|ico|mp3))#Usi', '<a$1href_sli_file=$2$3', $content);

        //Локализируем
        $allow = self::getAllowPregString();
        $host = preg_quote($_SERVER['HTTP_HOST']);
        $content = preg_replace('#<(a|base)(?! %)([^>]*)href=("|\')((/)(?!'.self::$language.'/)|(https?://'.$host.')(?!/'.self::$language.'/))('.($allow ? $allow : '[^>]*').')(?!\\\)\\3(.*)>#Usi', '<$1$2href=$3$6/'.self::$language.'$5$7$3$8>', $content);
        $content = preg_replace('#<form([^>]*)action=("|\')((/)(?!'.self::$language.'/)|(https?://'.$host.')(?!/'.self::$language.'/))('.($allow ? $allow : '[^>]*').')(?!\\\)\\2(.*)>#Usi', '<form$1action=$2$5/'.self::$language.'$4$6$2$7>', $content);
        $content = preg_replace('#(?:document\.)?location\.href\s*=\s*("|\')((/)(?!'.self::$language.'/)|(https?://'.$host.')(?!/'.self::$language.'/))('.($allow ? $allow : '.*').')(?!\\\)\\1#Ui', 'location.href=$1$4/'.self::$language.'$3$5$1', $content);
        $content = str_replace('<a %', '<a ', $content);


        $content = str_replace('href_sli_file=', 'href=', $content);
        $content = preg_replace('#href=("|\')([^>]+/'.self::$language.')\\1#Usi', 'href=$1$2/$1', $content);

        return $content;
    }
    
    /**
     * Получить локализованный адрес (только полные адреса начинающиеся на / или https?://)
     * @var string
     * @return string     
     */    
    public static function getLocalizedUrl($url)
    {        
    	if (self::getCurrentLanguage() && is_string($url) && ($url[0]=='/' || strpos($url, 'http://')!==false || strpos($url, 'https://')!==false)) {
            $allow = self::getAllowPregString();
            $url = preg_replace('#^((?:(/)(?!'.self::$language.'/))|(?:(https?://'.preg_quote($_SERVER['HTTP_HOST']).')(?!/'.self::$language.'/)))('.($allow ? $allow : '.*').')$#Ui', '$3/'.self::$language.'$2$4', $url);
        }
        
        return $url;    
    }

    /**
     * Получить строку для регулярного выражения - локализация разрешенных адресов
     * @return null|string
     */
    public static function getAllowPregString()
    {
        if (is_null(self::$_allowPregUrls)) {

            //Проверка на наличие разрешеенных адресов
            $allowUrls = self::getAllowUrls();
            //Локализация только для разрешенных адресов
            self::$_allowPregUrls = '';
            if ($allowUrls) {

                foreach ($allowUrls as $k=>$val) {
                    $allowUrls[$k] = ltrim($allowUrls[$k], '/');
                    $allowUrls[$k] = preg_quote($allowUrls[$k], '#');
                }

                self::$_allowPregUrls = '/?(?:'.implode('|', $allowUrls).')';
            }
        }

        return self::$_allowPregUrls;
    }
    
    /**
     * Проверить адрес на наличие правила для его игнорирования
     * @return boolean
     */
    public static function isIgnoreUrl($url)
    {
        $result = false;
        $rules = SLISettings::getInstance()->getVar('igroreUrls', true);
        
        if ($rules) {
            
            $rules = explode("\n", $rules);
            foreach ($rules as $rule) {
                
                $rule = trim($rule);
                if (!$rule) { continue;}
                
                //если правило не является регулярным выражением
                if ($rule[0]!='#' && mb_strtolower($rule, 'UTF-8')==mb_strtolower($url, 'UTF-8')) {
                    $result = true;
                    break;    
                }
                
                //если правило задано регулярным выражением
                if ($rule[0]=='#' && preg_match($rule.'#Uui', $url)) {
                    $result = true;
                    break;
                }
            }
        }
        
        return $result;
    }

    /**
     * Проверить адрес на наличие в базе разрешенных (если база пуста - считаем что разрешены все)
     * @return boolean
     */
    public static function isAllowUrl($url)
    {
        $result = true;
        $rules = self::getAllowUrls();

        $url = preg_replace('#\?.*$#', '', $url);

        if ($rules) {

            $result = false;

            foreach ($rules as $rule) {

                if (mb_strtolower($rule, 'UTF-8')==mb_strtolower($url, 'UTF-8')) {
                    $result = true;
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Получить список разрешенных адресов
     * @return array|null
     */
    public static function getAllowUrls()
    {
        if (is_null(self::$_allowUrls)) {

            self::$_allowUrls = SLISettings::getInstance()->getVar('allowUrls');
            self::$_allowUrls = explode("\n", self::$_allowUrls);
            foreach (self::$_allowUrls as $k=>$rule) {
                self::$_allowUrls[$k] = trim(self::$_allowUrls[$k]);
                if (!$rule) { unset(self::$_allowUrls[$k]);}
            }
        }

        return self::$_allowUrls;
    }

    /**
     * Проверить адрес на наличие контента исходя из которого нужно игнорить всю страницу
     * @return boolean
     */
    public static function isHaveIgnoreContent($content)
    {
        $result = false;
        $rules = SLISettings::getInstance()->getVar('ignorePageVsContent', true);
        
        if ($rules) {
            
            $rules = explode("\n", $rules);
            foreach ($rules as $rule) {
                
                $rule = trim($rule);
                if (!$rule) { continue;}
                
                //если правило не является регулярным выражением
                if ($rule[0]!='#' && strpos($content, $rule)!==false) {
                    $result = true;
                    break;    
                }

                //если правило задано регулярным выражением
                if ($rule[0]=='#' && preg_match($rule.'#Uui', $content)) {
                    $result = true;
                    break;
                }
            }
        }
        
        return $result;
    }
    
    /**
     * Получить локализованный контент
     * @var string
     * @return string     
     */
    public static function getLocalizedContent($content)
    {
    	$replaceData = array();

        //Проверяем данные кэширования
        $cacheContent = false;
        if (SLISettings::getInstance()->getVar('cacheStatus')) {
        	$cacheId = 'SLITranslate_Content:'.self::getCurrentLanguage().':'.md5($content);
            $cacheContent = SLICache::getInstance($cacheId)->get();
            if ($cacheContent) {
                $content = $cacheContent;
                $cacheContent = true;
            }
        }
        
        if (!SLISettings::getInstance()->getVar('cacheStatus') || !$cacheContent) {

	        //Заготовка для перевода, переменные вместо текстовых блоков
	        $pos = 0;

            //Получить список фраз на перевод
            $originalData = self::getTranslateList($content);

	        if (!empty($originalData['original'])) {

                //Подключаем массив переводов (далее используем self::$_tData)
                self::getTranslateData($originalData['search'], self::$language);

                foreach($originalData['original'] AS $k=>$v){

	                $clean = $originalData['clean'][$k];
	                $search = $originalData['search'][$k];
	    
	                //если перевод есть - заменяем              
	                if (isset(self::$_tData[$search]) && isset(self::$_tData[$search]['translate'])) {
	    				$isHaveTranslate = htmlspecialchars(self::$_tData[$search]['translate'], ENT_QUOTES);
	    				$replaceData[$search] = self::$_tData[$search];
	                } else {
	                	$isHaveTranslate = false;
	                }

	                if ($isHaveTranslate) {
	                    //Проверяем на изменение не переводимых частей в оригинале
	                    if (self::$_tData[$search]['original']!=$clean) {

                            //Заменяем измененные не переводимые части в значении перевода
                            preg_match_all('#(?:(?:&\#?[A-z0-9]{1,7};)|[\d,.|!?/\#*+=^~_&^%$@:©×()\[\]{}"\\\;])+#u', $clean, $symbols);
                            preg_match_all('#(?:(?:&\#?[A-z0-9]{1,7};)|[\d,.|!?/\#*+=^~_&^%$@:©×()\[\]{}"\\\;])+#u', $isHaveTranslate, $tSymbols);

                            $symbols = $symbols[0];
                            $tSymbols = $tSymbols[0];
	                    
	                        if (!empty($symbols)) {

	                            $sPos = 0;
	                            foreach ($symbols as $symbolKey=>$symbol) {

                                    //Апостроф не заменяем
                                    if (!isset($tSymbols[$symbolKey]) || $tSymbols[$symbolKey]=='&#039;') { continue;}
	                                $sPos = strpos($isHaveTranslate, $tSymbols[$symbolKey], $sPos);
	                                                            
	                                if ($sPos!==false) {
	                                    $isHaveTranslate = substr_replace($isHaveTranslate, $symbol, $sPos, strlen($tSymbols[$symbolKey]));
                                        $sPos += strlen($symbol);
	                                }    
	                            }
	                        }
	                    }

	                    //Проводим замену на перевод
	                    $pos = strpos($content, $originalData['match'][$k], $pos);
	                    $pos = strpos($content, $v, $pos);
	                    $content = substr_replace($content, $isHaveTranslate, $pos, strlen($v));            
	                } else {                
	                    //сохраняем в базу переводов
                        self::addOriginalText($clean, $search);
	                }
	            }            
	        }

	        //Подставляем переменные
	        $originalVars = SLIVars::getOriginalContent();
	        $translateVars = SLIVars::getLanguageContent(self::$language);
	        if (!empty($originalVars) && is_array($originalVars)) {
	        	foreach ($originalVars as $key => $var) {
	        		if (strpos($content, $var)===false || empty($translateVars[$key])) { continue;}
	        		$content = str_replace($var, $translateVars[$key], $content);
	        	}
	        }
	                                  
	        unset($match);    
	        
	        //сохраняем результат в кэш
	        if (SLISettings::getInstance()->getVar('cacheStatus') && !empty($content)) {
	        	SLICache::getInstance($cacheId)->set($content, SLISettings::getInstance()->getVar('cacheTime'));
	        }
        }
                          
        return $content;    
    }

    /**
     * Получить список фраз на перевод
     * @param $content
     * @return array
     */
    public static function getTranslateList($content)
    {
        $cleanKeys = $searchKeys = array();

        //очистка контента от комментов и скриптов
        $cleanContent = self::getCleanSearchContent($content);

        //Ищем переводимый контент
        preg_match_all(self::getLocalizedContentPattern(), $cleanContent, $match/*, PREG_OFFSET_CAPTURE*/);
        unset($match[1]);

        if (!empty($match[2])) {

            //Формируем список ключей для поиска по базе
            foreach($match[2] AS $k=>$v){
                //чистим от переводов строк
                $cleanKeys[$k] = self::getCleanText($v);
                //чистим от переводов не переводимых частей текста для поиска по базе
                $searchKeys[$k] = self::getSearchText($cleanKeys[$k]);
            }
        }

        return array(
            'match'     => $match[0],
            'original'  => $match[2],
            'clean'     => $cleanKeys,
            'search'    => $searchKeys,
        );
    }

    /**
     * Очистить контент страницы от переменных SLI
     * @param $content
     * @return mixed
     */
    public static function cleanSliVars($content)
    {
        $pattern = str_replace('%s', '.+', preg_quote(self::VAR_HTTP_PATTERN));
        return preg_replace('!'.$pattern.'!U', '', $content);
    }

    /**
     * Поиск новых данных на перевод
     * @param string $content
     */
    public static function searchNewTranslationData($content)
    {
    	//проверяем соответствие правилам игнорирования
    	if (!self::isIgnoreUrl($_SERVER['REQUEST_URI']) && !self::isHaveIgnoreContent($content)) {

            //Получить список фраз на перевод
            $originalData = self::getTranslateList($content);

	        if (!empty($originalData['original'])) {

                //Подключаем массив переводов
                self::getTranslateData($originalData['search'], self::$language);

	        	foreach($originalData['original'] AS $k=>$v){
		        	self::addOriginalText($originalData['clean'][$k], $originalData['search'][$k]);
		        }
	        }
    	}
    }
    
    /**
     * Получить контент без комментариев и скриптов для поиска заменяемых частей
     * @return string
     */
    public static function getCleanSearchContent($content)
    {
    	$ignoreTags = self::getIgnoreTags();
    	$regexp = array(
            '('.SLIApi::ignoreStart(false).'.*'.SLIApi::ignoreEnd(false).')',
            '(<!--.*-->)',
        );

        //Вырезаем игнорируеммые теги
    	foreach ($ignoreTags as $tag) { 
    		$tag = preg_quote($tag); 
    		if ($tag) { $regexp[] = '(<'.$tag.'[\s>].*</'.$tag.'>)';}
    	}

        $content = preg_replace('#'.implode('|', $regexp).'#Usi', '', $content);

        return $content;
    }
    
    /**
     * Получить список тегов контент которых игнорируется переводчиком
     * @return array
     */
    public static function getIgnoreTags()
    {
    	$ignoreTags = array('script', 'style');
    	$ignoreStr = SLISettings::getInstance()->getVar('ignoreTags');
    	
    	if ($ignoreStr) {
    		$ignoreTags = explode(',', $ignoreStr);
    		foreach ($ignoreTags as &$value) { $value = trim($value);}
    	}

    	return $ignoreTags;
    }
    
    /**
     * Получить регулярное выражение для переводимых частей
     * @return string
     */
    public static function getLocalizedContentPattern()
    {   	
		$visAttr = self::getVisibleAttr();
    	$regexp = array();
    	
    	foreach ($visAttr as $attr) { 
    		$attr = preg_quote($attr); 
    		if ($attr) { $regexp[] = '(?:'.$attr.')';}
    	}

    	return '#
          (?:(?:>|\A)|(?:\s+(?:'.implode('|', $regexp).')\s*=\s*("|\')))
                (?:(?:&\#?[a-z0-9]{1,7};)|[\s\d,.|!?/\#*+=^~`\-_&^%$@:©×()\[\]{}"\'\\\;])*
            		([a-zа-яёїі][^<]*[,.!?:;)}\]]?)(?![,.!?:;)}\]])
                (?:(?:&\#?[a-z0-9]{1,7};)|[\s\d,.|!?/\#*+=^~`\-_&^%$@:©×()\[\]{}"\'\\\;])*
  		  (?:(?:(?!\\\)\\1)|(?:<|\Z))
        #Uuxsi';
    }
    
    /**
     * Получить список переводимых аттрибутов
     * @return array
     */
    public static function getVisibleAttr()
    {
    	$ignoreTags = array('title', 'alt', 'placeholder', 'content');
    	$ignoreStr = SLISettings::getInstance()->getVar('visibleAttr');
    	
    	if ($ignoreStr) {
    		$ignoreTags = explode(',', $ignoreStr);
    		foreach ($ignoreTags as &$value) { $value = trim($value);}
    	}

    	return $ignoreTags;
    }     
    
    /**
     * Получить тект для поиска по базе
     * @var $text - string     
     */         
    public static function getSearchText($text)
    {
        $text = str_replace(array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '-', '–',
            '_', '-', '.', ',', '\\', '#', '!', '"', '\'', '$', ':', ';', '%', '^', '*', '(', ')', '+', '=', '~', '`',
            '\\', '/', '<', '>', '{', '}', '[', ']', ' ', '?',
            '@', '©', '×',
            ), '|', $text);
        //'№', '«', '»', - utf8
        return trim(preg_replace('!\|+!', '|', $text), '|');
    }

    /**
     * Получить текcт для перевода, очищенных от лишних пробелов и символов перевода строк
     * @var $text - string     
     */    
    public static function getCleanText($text)
    {
        return trim(str_replace('  ', ' ', str_replace(array("\r\n", "\r", "\n", '&nbsp;'), ' ', $text)));
    }
    
    /**
     * Получить массив с данными для перевода
     * @var $searchKeys - array
     * @var $lang - string
     * @return array
     */                   
    private static function getTranslateData($searchKeys, $lang)
    {

        //Удаляем те что уже достали с БД
        foreach ($searchKeys as $k=>$v) {
            if (isset(self::$_tData[$v])) { unset($searchKeys[$k]);}
        }

        if ($searchKeys) {

            //Формируем список аттрибутов для запроса в базу
            $request = self::createOriginalKeys($searchKeys);
            $cRequest = count($request);
            if ($cRequest) {

                $langId = self::getLangId($lang);

                /*if(!self::$_runAutoTranslate) {return array();}
                $status = SLICore::getInstance()->db()->prepare("FLUSH STATUS;");
                $status->execute();*/
                /*SQL_NO_CACHE*/

                $dataQuery = SLICore::getInstance()->db()->prepare("
                    SELECT o.`id`, o.`search`, o.`content` as `original`
                        ".($langId!==false ? ', t.`content` as `translate`' : ',NULL as `translate`')."
                        FROM `sli_original` AS `o`
                        FORCE INDEX(indexA)
                        ".($langId!==false ? "LEFT JOIN `sli_translate` AS `t` ON(`o`.`id`=`t`.`original_id` AND `t`.`language_id`=$langId)" : '')."
                    WHERE ".(implode('OR', array_fill(0, $cRequest, '(`a`=? AND BINARY `search`=?)')))."
                    LIMIT $cRequest
                ");

                $paramKey = 0;
                foreach ($request as $params) {foreach($params as $key=>$param){
                    $paramKey++;
                    $dataQuery->bindValue($paramKey, $param, PDO::PARAM_STR);
                }}

                $dataQuery->execute();

                /*$status = SLICore::getInstance()->db()->prepare("SHOW STATUS LIKE 'handler%';");
                $status->execute();
                if ($status->errorCode()!='00000') {
                    d($status->errorInfo());
                }
                d($status->fetchAll());
                d($dataQuery->fetchAll());*/

                while($row = $dataQuery->fetch())
                {
                    self::$_tData[$row['search']] = array(
                        'id' => $row['id'],
                        'search' => $row['search'],
                        'translate' => $row['translate'],
                        'original' => $row['original'],
                    );
                }
            }
        }

        return self::$_tData;
    }

    /**
     * Сгенерировать ключи для БД
     * @param array $searchKeys
     * @return array
     */
    public static function createOriginalKeys($searchKeys = array())
    {
        $keys = array();
        foreach ($searchKeys as $v) {
            $keys[] = array(
                'a' => mb_substr($v, 0, 64, 'utf8'),
                'search' => $v,
            );
        }

        return $keys;
    }

    /**
     * Добавить значение в массив оригиналов на перевод
     * @var $lang - string
     * @return boolean
     */    
    private static function addOriginalText($text, $search = '')
    {
        if (!$search) {
            $search = self::getSearchText($text);
        }

        if ($search && !isset(self::$_tData[$search])) {

            self::$_originalData[$search] = $text;

            return true;
            
        } else {
            return false;
        }                                  
    }

    /**
     * Сохранить файл оригиналов
     * @return boolean
     */
    private static function saveOriginalData()
    {
        if (!self::$_originalData) {
            return false;
        }

        $request = self::createOriginalKeys(array_keys(self::$_originalData));
        $cRequest = count($request);

        if ($cRequest) {

            $dataQuery = SLICore::getInstance()->db()->prepare("
                    INSERT LOW_PRIORITY INTO `sli_original` (`a`, `search`, `content`) VALUES
                    ".(implode(', ', array_fill(0, $cRequest, '(?, ?, ?)'))).";
                ");

            $paramKey = 0;
            foreach ($request as $params) {
                $params['content'] = self::$_originalData[$params['search']];
                foreach($params as $param){$paramKey++;
                    $dataQuery->bindValue($paramKey, $param, PDO::PARAM_STR);
                }
            }

            $dataQuery->execute();

            self::$_originalData = array();
        }

        return true;
    }
    
	/**
	 * Получить выбранный язык
	 * @return string
	 */
	public static function getCurrentLanguage()
	{
        if (is_null(self::$language)) {
            
            //для авторизированных доступны все языки
            $onlyActive = SLIAdmin::getUser() ? false : true;
            
            //парсим урл
            preg_match('!^/('.implode('|', self::getLanguages($onlyActive)).')/?!', $_SERVER['REQUEST_URI'], $match);
            self::$language = !empty($match[1]) ? $match[1] : false;   
        }
		
		return self::$language;
	}

    /**
     * Получить список алиасов языковых версий
     * @param bool $onlyActive
     * @param bool $getAllParam
     * @return array
     */
    public static function getLanguages($onlyActive = false, $getAllParam = false)
	{
        $aliases = array();
        $languages = SLISettings::getInstance()->getVar(SLISettings::LANGUAGES_VAR);

        foreach ($languages as $k=>$val) { 
            
            if ($onlyActive===true && !$val['isActive']) { 
                continue;
            } 
            
            if ($getAllParam) {
                $aliases[] = $val;
            } else {
                $aliases[] = $val['alias'];    
            }
            
        }

		return $aliases;
	}

    /**
     * Получить id языка
     * @param string $language
     * @return int
     */
    public static function getLangId($language)
    {
        return array_search($language, self::getLanguages());
    }

    /**
     * Получить id языка
     * @param int $languageId
     * @return string
     */
    public static function getLangAlias($languageId)
    {
        $languages = self::getLanguages();
        return !empty($languages[$languageId]) ? $languages[$languageId] : false;
    }

	/**
	 * Сохранить значение перевода
	 */
	public static function saveTranslate($id, $language, $content)
	{
		if (($languageId = self::getLangId($language))!==false) {

            $id = (int)$id;
            $db = SLICore::getInstance()->db();
            $upd = $db->prepare("
                INSERT INTO `sli_translate` (`original_id`, `language_id`, `content`)
                VALUES (:id, :langId, :content)
                ON DUPLICATE KEY UPDATE `content`=:content
            ");
            $upd->bindParam(':content', $content, PDO::PARAM_STR);
            $upd->bindParam(':id', $id, PDO::PARAM_INT);
            $upd->bindParam(':langId', $languageId, PDO::PARAM_INT);
            $upd->execute();
		}
	}
	
	/**
	 * Удалить элемент перевода
	 * @param mixed $id - integer или array из id элементов
	 */
	public static function deleteItem($id)
	{
        $result = false;
        $data = array();

        //удаляем айтемы
        if (!is_array($id)) {
            $id = array((int)$id);
        } else {
            foreach ($id as $k=>$v) {
                $id[$k] = (int)$v;
                if (!$id[$k]) { unset($id[$k]);}
            }
        }

        $result = true;
        $db = SLICore::getInstance()->db();

        if ($id) {
            $inQuery = implode(',', $id);
            $upd = $db->prepare("
                DELETE FROM `sli_original` WHERE `id` IN ($inQuery);
                DELETE FROM `sli_translate` WHERE `original_id` IN ($inQuery);
            ");
            $upd->execute();
        }

        return $result;
	}   
	
    /**
     * Очистить данные массива от последнего переноса строки
     * @param array $array
     */
    public static function arrayNtrim(&$array)
    {
		$n = count($array);
		for ($i=0;$i<$n;$i++){ $array[$i] = rtrim($array[$i], "\r\n");}
    }
}