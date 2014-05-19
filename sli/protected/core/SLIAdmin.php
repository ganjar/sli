<?php
/**
 * SLIAdmin
 * Клас админки
 * @author Ganjar@ukr.net
 */

class SLIAdmin {
    
    const AUTH_ACTION 		= 'auth';
    const REGISTER_ACTION 	= 'registration';
    const AUTH_SESSION_NAME = 'SLI:AUTH';

    /**
     * Соль для шифровки паролей
     */
    const SALT = 'IK&(Iujasdaki';
    
    /**
     * Кол-во элементов на странице
     */
    const PAGE_SIZE = 40;
    
    /**
     * Шаблон для подключения прредставлений админки
     */
    const TEMPLATE_PATTERN = '%s/views/%s.php';
	
    const LAYOUT_PATTERN = '%s/views/layouts/%s.php';
    
    const URL_PATTERN = 'index.php?action=%s';
    
    private static $action = 'index';
    
    private static $title = 'Админка';
    
    /**
     * Пользователь админки
     * @var boolean
     */
    private static $_user;
    
    public static function init()
    {
    	session_start();
    	
        //Проверка аутентификации
        self::isAuth();
        
        if (!empty($_GET['action']) && method_exists(__CLASS__, 'action'.$_GET['action'])) { 
            self::$action = $_GET['action'];
        }
        
        if (!empty(self::$action)) {
            $actionName = 'action'.self::$action;
            self::$actionName();
        }
    }
    
    /**
     * Проверка аутентификации пользователя
     */
    public static function isAuth()
    {
        $user = self::getUser();

        if (!$user) {
        	
        	//первая авторизация, показываем форму регистрации
        	if (is_null($user)) {
        		$actionName = 'action'.self::REGISTER_ACTION;
        	} 
        	
        	//пользователь не авторизирован, открываем форму авторизации
        	else {
        		$actionName = 'action'.self::AUTH_ACTION;
        	}
        	
        	self::$actionName();
        	exit;
        }
        
        return $user;
    }
    
    /**
     * Получить пользователя админки
     */
    public static function getUser()
    {
    	if (is_null(self::$_user)) {
    		
    		$isAuth = false;
	    	$admin = SLISettings::getInstance()->getVar('admin');

			if (!empty($admin['login']) && !empty($admin['password'])) {
	        	if (!empty($_SESSION[self::AUTH_SESSION_NAME]) && !empty($_SESSION[self::AUTH_SESSION_NAME]['login']) && !empty($_SESSION[self::AUTH_SESSION_NAME]['password'])) {
	        		$isAuth = $_SESSION[self::AUTH_SESSION_NAME]['login']==$admin['login'] && $_SESSION[self::AUTH_SESSION_NAME]['password']==$admin['password'];
	        	}
	        	
				if ($isAuth) {
		        	self::$_user = $admin;
		        } else {
		        	self::$_user = false;
		        }	        	
	        }
    	}

    	return self::$_user;
    }
    
    /**
     * Получить логин пользователя админки
     */
    public static function getUserName()    
    {
    	$user = self::getUser();
    	return $user ? $user['login'] : '';
    }
    
    /**
     * Получить список табов
     */
    public static function getTabs()
    {
       return array(
            'translate' => array ('title' => 'Перевод', 'action' => 'index',),
            'vars'      => array ('title' => 'Переменные', 'action' => 'vars',),
            'scanner'    => array ('title' => 'Сканер', 'action' => 'scanner',),
            'settings'  => array ('title' => 'Настройки', 'action' => 'settings',),
       ); 
    }
    
    /**
     * Получить ссылку на екшин в админке
     */
    public static function getUrl($action, $params = null)
    {
        if (is_array($params)) { foreach ($params as $k=>$v) { $action .= '&'.$k.($v? '='.$v : '');}}
        return sprintf(self::URL_PATTERN, $action); 
    }
    
    /**
     * Отрисовываем шаблон
     */
    public static function renderPage($templateName, $vars = array(), $layout = 'admin')
    {
        $renderFile = sprintf(self::TEMPLATE_PATTERN, SLI_WORK_DIR, $templateName);
        $layOutFile = sprintf(self::LAYOUT_PATTERN, SLI_WORK_DIR, $layout); 
        
        $title = self::$title;
                
        if (file_exists($renderFile) && file_exists($layOutFile)) {
            
            if ($vars) { foreach ($vars as $k=>$v) { $$k = $v;}}
            
            ob_start();
            include $renderFile;
            $content = ob_get_contents();
            ob_end_clean();
                       
            include $layOutFile;
        }
    }
    
    /**
     * Отрисовываем часть страницы
     */
    public static function renderPartial($templateName, $vars = array(), $getContent = false)
    {
        $renderFile = sprintf(self::TEMPLATE_PATTERN, SLI_WORK_DIR, $templateName);
                
        if (file_exists($renderFile)) {
            
            if ($vars) { foreach ($vars as $k=>$v) { $$k = $v;}}
            
            $getContent ? ob_start() : '';
            
            include $renderFile;
            
            if ($getContent) {
	            $content = ob_get_contents();
	            ob_end_clean();
	            return $content;
            }
        }
    }    
    
    /**
     * Авторизация
     */
    private static function actionAuth()
    {
    	self::$title = 'Авторизация';
    	
    	$admin = SLISettings::getInstance()->getVar('admin');
		if (!empty($_REQUEST['LoginForm']) && !empty($_REQUEST['LoginForm']['login']) && !empty($_REQUEST['LoginForm']['password'])) {

			if ($_REQUEST['LoginForm']['login']==$admin['login'] && self::adminEncryp($_REQUEST['LoginForm']['password'])==$admin['password']) {
				$_SESSION[self::AUTH_SESSION_NAME]['login'] = $admin['login'];
				$_SESSION[self::AUTH_SESSION_NAME]['password'] = $admin['password'];
				
    			header('Location: '.$_SERVER['REQUEST_URI']);
    			exit;				
			}
		}
    	
        self::renderPage('auth', array(), 'auth');
    }
    
    /**
     * Регистрация
     */
    private static function actionRegistration()
    {
    	self::$title = 'Регистрация';

    	$errors = array();
    	
    	if (!empty($_REQUEST['LoginForm'])) {
    		
    		if (!empty($_REQUEST['LoginForm']['login']) && filter_var($_REQUEST['LoginForm']['login'], FILTER_VALIDATE_EMAIL)) {
    			$data['login'] = $_REQUEST['LoginForm']['login'];
    		} else {
    			$errors['login'] = 'Не верно заполнено поле E-mail';
    		}
    		
    		if (!empty($_REQUEST['LoginForm']['password']) && strlen($_REQUEST['LoginForm']['password'])>=4 && $_REQUEST['LoginForm']['password']==$_REQUEST['LoginForm']['applypassword']) {
    			$data['password'] = self::adminEncryp($_REQUEST['LoginForm']['password']);
    		} else {
    			if ($_REQUEST['LoginForm']['password']!=$_REQUEST['LoginForm']['applypassword']) {
    				$errors['applypassword'] = 'Пароли должны совпадать';	
    			} else {
    			    $errors['password'] = strlen($_REQUEST['LoginForm']['password'])<4 ? 'Пароль не может быть короче 4-х символов' : 'Не заполнено поле пароль';	
    			}
    		}

            if (!SLICore::getInstance()->db()) {
                $errors['db'] = 'Не установлено соеденение с MySQL';
            } else {
                //Создаем нужные таблицы в БД
                $installSQL = file_get_contents(SLI_WORK_DIR.'/install/install.sql');
                SLICore::getInstance()->db()->query($installSQL)->execute();
            }
    		
    		if (!$errors) {
    			SLISettings::setVar('admin', $data);
    			header('Location: '.$_SERVER['REQUEST_URI']);
    			exit;
    		}
    		
    	}
    	
        self::renderPage('registration', array('errors' => $errors,), 'auth');
    }
    
    /**
     * Выйти из админки
     */
    public static function actionLogout()
    {
    	if (!empty($_SESSION[self::AUTH_SESSION_NAME])) {
    		unset($_SESSION[self::AUTH_SESSION_NAME]);
    	}
    	
    	header('Location: '.$_SERVER['REQUEST_URI']);
    	exit;
    }
    
    /**
     * Шифруем пароль
     * @param string $password
     */
    public static function adminEncryp($password)
    {
    	return md5($password.self::SALT);
    }

    /**
     * Главная админки
     */
    private static function actionIndex()
    {
    	self::$title = 'Перевод';
    	
        $showSearch = false;
        $settings = SLISettings::getInstance();
        $languages = $settings->getVar(SLISettings::LANGUAGES_VAR);
        
        if (!empty($languages)) {
            
            //данные фильтра
            !empty($_REQUEST['search']) ? $showSearch = true : $_REQUEST['search'] = array();
            
            $search = self::getFilterVars();

            //определяем активный язык
            if (!empty($search['language'])) {
                foreach ($languages as $key=>$value) {
                    if ($search['language']==$value['alias']) {
                        $language = $value;
                        break;
                    }
                }
            }

            empty($language) ? $language = @array_shift($settings->getVar(SLISettings::LANGUAGES_VAR)) : '';

            //применяем фильтр
            $query = self::getFilterQuery($search);

            //Получаем страницу на которой остановились
	        $lastPage = self::getLastPage($search);

            //применяем лимит вывода
            if (isset($_REQUEST['getJson'])) {
                $page = !empty($_REQUEST['page']) ? (int)$_REQUEST['page'] : 0;
                $offset = $page ? ($page-1)*self::PAGE_SIZE : 0;
                $query['limit'] = 'LIMIT '.$offset.', '.self::PAGE_SIZE;
            } else {
                $query['limit'] = 'LIMIT '.self::PAGE_SIZE*$lastPage;
            }

            $dataQuery = SLICore::getInstance()->db()->prepare("
                SELECT SQL_CALC_FOUND_ROWS o.`id`, o.`search`, o.`content` as `original`, t.`language_id`, t.`content` as `translate`
                    FROM `sli_original` AS `o`
                    LEFT JOIN `sli_translate` AS `t` ON(`t`.`original_id`=`o`.`id`)
                WHERE $query[where] $query[order] $query[limit]
            ");

            $translate = $original = array();

            $dataQuery->execute();
            while($row = $dataQuery->fetch())
            {
                $original[$row['id']] = $row['original'];
                if ($row['translate']) {
                    $langAlias = SLITranslate::getLangAlias($row['language_id']);
                    if ($langAlias) {
                        $translate[$langAlias][$row['id']] = $row['translate'];
                    }
                }
            }

            //кол-во страниц
            $countAllQuery = SLICore::getInstance()->db()->prepare("SELECT FOUND_ROWS()");
            $countAllQuery->execute();
            $countAll = $countAllQuery->fetchColumn();
            $allPages = ceil($countAll/self::PAGE_SIZE);

            //Задаем страницу на которой остановились
	        !empty($_REQUEST['page']) ? self::setLastPage($_REQUEST['page'], $search) : '';

            //подготавливаем данные для шаблона
            $pageParams = array(
                'search'        => $search,
                'language'      => $language,
                'languages'     => $languages,                

                'original'      => $original,
                'translate'     => $translate,
                'allPages'     	=> $allPages,
                'lastPage'     	=> $lastPage,

                'showSearch'    => $showSearch,
            );
            
            if (isset($_REQUEST['getJson'])) {
				$ajaxData['result'] = self::renderPartial('indexAjaxPage', $pageParams, true);
            	echo json_encode($ajaxData);
            	exit;
            }

            self::renderPage('index', $pageParams);
        } else {
            self::renderPage('emptyLanguages');
        }
    }
    
    /**
     * Получить данные последней страницы с индексом формы поиска
     * @param array $search - значения поиска
     * @return array
     */
    private static function getLastPage($search)
    {
    	$page = 1;
    	$sessionPageName = self::getLastPageName();
        
    	if (!empty($_SESSION[$sessionPageName]) && $_SESSION[$sessionPageName]['index']==md5(serialize($search))) {

    		$page = $_SESSION[$sessionPageName]['page'];
            
            //временно. проблема с проработкой JS для большого объема
            $page>3 ? $page = 3 : '';  
            
    	}
    	
        
        return $page;
    }
    
    /**
     * Запоминаем страницу на которой остановились
     * @param int $page - страница
     * @param array $search - значения поиска
     */
    private static function setLastPage($page, array $search)
    {
    	$sessionPageName = self::getLastPageName();
        
    	$_SESSION[$sessionPageName] = array(
        	'index' => md5(serialize($search)),
        	'page' 	=> $page,
        );
    	
        
        return $_SESSION[$sessionPageName];    	
    }
    
    /**
     * Получить имя сессии последней страницы
     * @return string
     */
    private static function getLastPageName()
    {
    	return __CLASS__.':'.self::$action.':page';
    }
    
    /**
     * Сохраняем перевод
     */
    private static function actionSaveTranslate()
    {
        if (!empty($_POST['language']) && isset($_POST['idContent']) && isset($_POST['content'])) {
        	SLITranslate::saveTranslate($_POST['idContent'], $_POST['language'], $_POST['content']);
        }
    }

    /**
     * Удаляем оригинал и все связанные переводы
     */
    private static function actionDeleteTranslate()
    {
        if (isset($_POST['idContent']) && is_array($_POST['idContent'])) {
        	
        	echo SLITranslate::deleteItem($_POST['idContent']) ? 1 : 0;           
            exit;   
        }
    }
    
    /**
     * Переменные
     */
    private static function actionVars()
    {
    	self::$title = 'Переменные';
    	
        $showSearch = false;
        $settings = SLISettings::getInstance();
        $languages = $settings->getVar(SLISettings::LANGUAGES_VAR);
        
        if (!empty($languages)) {
        	
        	//создаем переменную
        	if (!empty($_POST['create']) && !empty($_POST['create']['original'])) {
				
        		SLIVars::saveVar($_POST['create']);        		
        		header('Location: '.$_SERVER['REQUEST_URI']); 
                exit;
        	}
            
            //данные фильтра
            !empty($_REQUEST['search']) ? $showSearch = true : $_REQUEST['search'] = array();
            
            $search = self::getFilterVars();

            //определяем активный язык    
            if (!empty($search['language'])) {
                foreach ($languages as $value) {
                    if ($search['language']==$value['alias']) {
                        $language = $value;
                        break;
                    }
                }
            }

            empty($language) ? $language = @array_shift($settings->getVar(SLISettings::LANGUAGES_VAR)) : '';

            //подготавливаем список перевода
            $translate = array();
            foreach($languages as $langValue) { $translate[$langValue['alias']] = SLIVars::getLanguageContent($langValue['alias']);}
            $original = SLIVars::getOriginalContent();
            
            //применяем фильтр
            self::applyFilter($original, $translate, $search);
            
            //кол-во страниц
			$allPages = ceil(count($original)/self::PAGE_SIZE);
			
			//Получаем страницу на которой остановились
	        $lastPage = self::getLastPage($search);
				        
            //применяем лимит вывода
            if (isset($_REQUEST['getJson'])) {
	            $page = !empty($_REQUEST['page']) ? (int)$_REQUEST['page'] : 0;
	            $offset = $page ? ($page-1)*self::PAGE_SIZE : 0;
	            $original = array_slice($original, $offset, self::PAGE_SIZE, true);
            } else {
	            $original = array_slice($original, 0, self::PAGE_SIZE*$lastPage, true);
            }
            
            //Задаем страницу на которой остановились
	        !empty($_REQUEST['page']) ? self::setLastPage($_REQUEST['page'], $search) : '';

            //подготавливаем данные для шаблона
            $pageParams = array(
                'search'        => $search,
                'language'      => $language,
                'languages'     => $languages,                
                'original'      => $original,
                'translate'     => $translate,
                'allPages'     	=> $allPages,
                'lastPage'     	=> $lastPage,
                'showSearch'    => $showSearch,
            );
            
            if (isset($_REQUEST['getJson'])) {
				$ajaxData['result'] = self::renderPartial('varsAjaxPage', $pageParams, true);
            	echo json_encode($ajaxData);
            	exit;
            }

            self::renderPage('vars', $pageParams);
        } else {
            self::renderPage('emptyLanguages');
        }
    }
    
    /**
     * Сохраняем переменную
     */
    private static function actionSaveVarTranslate()
    {
        if (isset($_POST['idContent']) && ((!empty($_POST['language']) && isset($_POST['content'])) || !empty($_POST['content']))) {
        	
        	//Сохраняем переменную
        	SLIVars::saveTranslate($_POST['idContent'], !empty($_POST['language']) ? $_POST['language'] : false, $_POST['content']);
        }
    }
    
    /**
     * Удаляем оригинал и все связанные переменные
     */
    private static function actionDeleteVar()
    {
        if (isset($_POST['idContent']) && is_array($_POST['idContent'])) {
        	
        	echo SLIVars::deleteItem($_POST['idContent']) ? 1 : 0;            
            exit;   
        }
    }  
    
    /**
     * Настроки
     */
    private static function actionSettings()
    {
    	self::$title = 'Настройки';
    	
        $selectLanguage = 'en';
        $settings = SLISettings::getInstance();
        $admin = $settings->getVar('admin');
        
        if (!empty($_POST['settings'])) {
            foreach ($_POST['settings'] as $k=>$value) {
            	
            	if ($k=='admin') {
            		
	            	if ((!empty($value['password']) || $value['login']!=$admin['login']) && self::adminEncryp($value['old_password'])==$admin['password'] && filter_var($value['login'], FILTER_VALIDATE_EMAIL)) {
	            		
	            		if (!empty($value['password']) && strlen($value['password'])>=4 && $value['applypassword']==$value['password']) {
	            			$value['password'] = self::adminEncryp($value['password']);	
	            		} else {
	            			$value['password'] = $admin['password'];
	            		}
	            		
	            		unset($value['old_password'], $value['applypassword']);
	            		
	            		$settings->setVar($k, $value);
	            	}            		
	            	
            	} else {

            		if ($k==SLISettings::LANGUAGES_VAR) { 
	                    foreach ($value as $langKey=>$langVal) { 
	                        if (empty($langVal['alias'])) { 
	                            unset($value[$langKey]);
	                        }
	                    }
	                }
	                $settings->setVar($k, $value);
            	}
            }
            header('Location: '.$_SERVER['REQUEST_URI']);
            exit;
        }
        
        self::renderPage('settings', array(
            'settings'      => $settings,
            'admin' 	    => $admin,
        ));
    }        
    
    /**
     * Получить список языков используемых для автоперевода
     * @return array
     */
    public static function getTranslateLanguages()
    {
    	return SLISettings::getTranslateLanguages();
    }

    /**
     * Получить дропдаун для выбора языка
     */
    public static function getTranslateLanguagesHtmlOptions($selected = '')
    {
    	return self::getHtmlOptions(self::getTranslateLanguages(), $selected);
    }
    
    /**
     * Получить опции дропдауна
     */
    public static function getHtmlOptions(array $data, $selected = '')
    {
    	$html = '';
    	foreach ($data as $alias => $title) { $html .= '<option value="'.$alias.'"'.($alias==$selected? ' selected="selected"' : '').'>'.$title.'</option>';}
    	return $html;
    }

    /**
     * Получить запрос фильтра
     * @param array $search
     */
    private static function getFilterQuery(array $search)
    {
        $query = array(
            'where' => array(),
            'order' => '',
            'limit' => '',
        );
        $languages = SLISettings::getInstance()->getVar(SLISettings::LANGUAGES_VAR);

        //поиск по id
        if ($search['id']!==null) {
            $query['where'][] = 'o.id='.(int)$search['id'];
        } else {

            //Поиск по фразе
            if ($search['original']) {
                $query['where'][] = 'o.content LIKE "'.str_replace('*', '%', addslashes($search['original'])).'"';
            }

            //Поиск по URL
            if ($search['url']) {

                $content = SLIScanner::getContent($search['url']);
                $originalData = SLITranslate::getTranslateList($content);

                if (!empty($originalData['search'])) {

                    //Формируем список аттрибутов для запроса в базу
                    $request = SLITranslate::createOriginalKeys($originalData['search']);
                    $query['where']['url'] = array();
                    $urlWhere = array();
                    foreach ($request as $k=>$v) {
                        $urlWhere[] = '(o.`a`="'.$v['a'].'" AND o.`search`="'.$v['search'].'")';
                    }
                    $query['where']['url'] = '('.implode('OR', $urlWhere).')';
                } else {
                    $query['where']['url'] = '0';
                }
            }

            //Поиск по переводу
            if ($search['translate']) {
                $query['where'][] = 't.content LIKE "'.str_replace('*', '%', addslashes($search['translate'])).'"';
            }

            //показываем только непереведенные айтемы
            if ($search['show_empty']) {
                $languageId = !empty($search['language']) ? SLITranslate::getLangId($search['language']) : 0;
                $query['where'][] = '(((t.content="" OR t.content IS NULL) AND t.language_id="'.$languageId.'") OR o.id NOT IN (
                    SELECT `sli_original`.`id` FROM `sli_original` , `sli_translate` WHERE `sli_original`.`id` = `sli_translate`.`original_id`  AND `sli_translate`.`language_id`="'.$languageId.'"
                ))';
            }

            //сортировка
            switch ($search['sort']) {
                case 'id-desc':
                    $query['order'] = 'ORDER BY o.`id` DESC';
                    break;
                case 'id-asc':
                    $query['order'] = 'ORDER BY o.`id` ASC';
                    break;
                case 'original-asc':
                    $query['order'] = 'ORDER BY o.`search` ASC';
                    break;
                case 'original-desc':
                    $query['order'] = 'ORDER BY o.`search` DESC';
                    break;
            }
        }

        $query['where'] = count($query['where']) ? implode(' AND ', $query['where']) : '1';

        return $query;
    }

    /**
     * Применить фильтр к значениям массива
     * @param array $original
     * @param array $translate
     * @param array $search
     */
    private static function applyFilter(array &$original, array &$translate, array $search)
    {
    	$languages = SLISettings::getInstance()->getVar(SLISettings::LANGUAGES_VAR);
    	
		//поиск по id
        if ($search['id']!==null) {
            $original = !empty($original[$search['id']]) ? array($search['id'] => $original[$search['id']],) : array();     
        } else {

            //Поиск по фразе
            if ($search['original']) {
                $originalIds = self::arraySearch($original, $search['original']);

                if ($originalIds) {
                    $originalTmp = array();
                    foreach ($originalIds as $key => $originalId) { $originalTmp[$originalId] = $original[$originalId];}
                    $original = $originalTmp;
                } else {
                    $original = array();
                }
            }

            //Поиск по URL
            if ($search['url']) {
                $originalIds = array();
                $additionalData = SLITranslate::getAdditionalData();

                //Поиск по маске
                if (!empty($search['url'][0]) && $search['url'][0]=='*') {
                    $search['url'] = substr($search['url'], 1);
                } else { $search['url'] = '"'.$search['url'];}
                $lastChar = strlen($search['url'])-1;
                if ($lastChar && $search['url'][$lastChar]=='*') {
                    $search['url'] = substr($search['url'], 0, -1);
                } else { $search['url'] = $search['url'].'"';}

                $dataIds = self::arraySearch($additionalData, $search['url']);
                foreach ($dataIds as $dataKey) {
                    $originalIds[] = $dataKey;
                }

                if ($originalIds) {
                    $originalTmp = array();
                    foreach ($originalIds as $key => $originalId) {
                        if (empty($original[$originalId])) { continue;}
                        $originalTmp[$originalId] = $original[$originalId];
                    }
                    $original = $originalTmp;
                } else {
                    $original = array();
                }
            }

            //Поиск по переводу
            if ($search['translate'] && !empty($translate[$search['language']]) && $original) {
            	
            	$translateIds = self::arraySearch($translate[$search['language']], $search['translate']);
            	                
                if ($translateIds) {
                    $translateTmp = array();
                    foreach ($translateIds as $key => $translateId) { $translateTmp[$translateId] = $translate[$search['language']][$translateId];}
                    foreach ($translateIds as $key => $originalId) { $originalTmp[$originalId] = $original[$originalId];}
                    $translate[$search['language']] = $translateTmp;
                    $original = $originalTmp;
                } else {
                    $translate[$search['language']] = array();
                    $original = array();
                }
            }
            
            //показываем только непереведенные айтемы
            if ($search['show_empty'] && !empty($translate[$search['language']]) && $original) {
                foreach($translate[$search['language']] as $key=>$value) {
                    if (trim($value)) { unset($original[$key]);} 
                }
            }
            
            //сортировка
            switch ($search['sort']) {
                case 'id-desc':
                    $original = array_reverse($original, true);
                    break;
                case 'id-asc':
                    break;
                case 'original-asc':
                    asort($original);
                    break;
                case 'original-desc':
                    arsort($original);
                    break;                                            
            }                                
        }    	
    }
    
    /**
     * Получить значения фильтра
     * @return array
     */
    private static function getFilterVars()
    {
    	return	array (
	        'id'            => isset($_REQUEST['search']['id']) && $_REQUEST['search']['id'] ? (int)$_REQUEST['search']['id'] : null,
	        'original'      => !empty($_REQUEST['search']['original']) ? stripcslashes($_REQUEST['search']['original']) : '',
	        'url'           => !empty($_REQUEST['search']['url']) ? stripcslashes($_REQUEST['search']['url']) : '',
	        'sort'          => !empty($_REQUEST['search']['sort']) ? stripcslashes($_REQUEST['search']['sort']) : 'id-desc',
	        'translate'     => !empty($_REQUEST['search']['translate']) ? stripcslashes($_REQUEST['search']['translate']) : '',
	        'show_empty'    => !empty($_REQUEST['search']['show_empty']) ? (int)$_REQUEST['search']['show_empty'] : 0,
	        'language'      => !empty($_REQUEST['search']['language']) ? stripcslashes($_REQUEST['search']['language']) : ''
        );
    }
    
    /**
     * Получить список возможных сортировок списка
     */    
    public static function getSortList()
    {
        return array(
            'id-desc'       => 'От новых к старым',
            'id-asc'        => 'От старых к новым',
            'original-asc'  => 'От А-Я',
            'original-desc' => 'От Я-А',
        );
    }
    
    /**
     * Поиск по массиву
     */
    public static function arraySearch(array $dataList, $search = '')
    {
        $idList = array();
        
        if ($search) {
            foreach ($dataList as $key=>$value) {
                if (mb_stripos($value, $search, 0, 'utf-8')!==false) {
                    $idList[] = $key;            
                }    
            }            
        }
        
        return $idList;
    }
    
    /**
     * Сканер страниц
     */
    public static function actionScanner()
    {
        self::renderPage('scanner');
        
        if (isset($_POST['scanner']) && isset($_POST['scanner']['url'])) {

            $_POST['scanner']['last'] = !empty($_POST['scanner']['last']) ? $_POST['scanner']['last'] : 0;
            $_POST['scanner']['type'] = !empty($_POST['scanner']['type']) ? (int)$_POST['scanner']['type'] : 0;

            SLIScanner::run(array(
                'type'  => $_POST['scanner']['type'],
                'url'   => $_POST['scanner']['url'],
                'last'  => $_POST['scanner']['last'],
            ));
        }
    }

    /**
     * Получить алиас текущего таба
     * @return string
     */
    public static function getAction()
    {
        return self::$action;
    }

    /**
     * Импорт данных из файла переводов
     */
    public static function importUaData()
    {
        ignore_user_abort(true); set_time_limit(0);

        $langId = 0;
        $langAlias = 'ua';

        $uaData = file('D:\server\domains\bodo.new\trunk\sli\data\languages\ua.txt');
        $searchData = file('D:\server\domains\bodo.new\trunk\sli\data\languages\original\search.dat');
        foreach ($uaData as $key=>$value) {

            $value = trim($value);
            if (!$value) {
                continue;
            }

            $dataQuery = SLICore::getInstance()->db()->prepare("
                SELECT o.`id`, o.`search`, o.`content` as `original`
                FROM `sli_original` AS `o`
                WHERE `search`='".trim($searchData[$key])."'
            ");

            $dataQuery->execute();
            $row = $dataQuery->fetch();

            if (!empty($row['id'])) {
                SLITranslate::saveTranslate($row['id'], $langAlias, $value);
            }
        }
    }

    /**
     * Задать разрешенные к отдаче, переведенные страницы
     * @param array $siteMapList
     * @param $languageId
     */
    public static function setAllowTranslatePages($siteMapList = array(), $languageId)
    {
        $languageId = (int)$languageId;
        $parseUrls = array();
        $allowUrls = SLITranslate::getAllowUrls();

        foreach ($siteMapList as $parseUrl) {

            $query = array(
                'where' => array(),
                'limit' => 'LIMIT 1',
            );

            $parseUrl = preg_replace('#http://.*/#Ui', '/', $parseUrl);

            //Не допускаем повторного сканирования URL
            if (!isset($parseUrls[$parseUrl]) && array_search($parseUrl, $allowUrls)===false) {

                $parseUrls[$parseUrl] = $parseUrl;

                echo 'Scan url: '.$parseUrl."\n";

                //Получаем контент страницы
                $content = SLIScanner::getContent($parseUrl);
                //Получаем список фраз на перевод
                $originalData = SLITranslate::getTranslateList($content);

                if (!empty($originalData['search'])) {

                    //Формируем список аттрибутов для запроса в базу
                    $request = SLITranslate::createOriginalKeys($originalData['search']);
                    $query['where']['url'] = array();
                    $urlWhere = array();
                    foreach ($request as $k=>$v) {
                        $urlWhere[] = '(o.`a`="'.$v['a'].'" AND o.`search`="'.$v['search'].'")';
                    }

                    $query['where']['url'] = '('.implode('OR', $urlWhere).') AND (t.`content` IS NULL OR t.`content`="")';
                    $query['where'] = implode(' AND ', $query['where']);

                    $dataQuery = SLICore::getInstance()->db()->prepare("
                                SELECT o.*
                                    FROM `sli_original` AS `o`
                                    LEFT JOIN `sli_translate` AS `t` ON(`t`.`original_id`=`o`.`id` AND `t`.`language_id`='$languageId')
                                WHERE $query[where] $query[limit]
                            ");
                    $dataQuery->execute();

                    if (!$dataQuery->fetchAll()) {
                        $allowUrls[$parseUrl] = $parseUrl;
                    }
                }
            }
        }

        //Сохраняем разрешенные URL
        if ($allowUrls) {
            SLISettings::getInstance()->setVar('allowUrls', implode("\n", $allowUrls));
        }
    }
}