<?php
/**
 * Site Language Injection
 * Модуль подключения к сайту
 * @author GANJAR
 * @link http://sli.su/
 */
session_start();
define('SLI_WORK_DIR', dirname(__FILE__));
require_once SLI_WORK_DIR.'/core/SLICore.php';
require_once SLI_WORK_DIR.'/core/SLISettings.php';
require_once SLI_WORK_DIR.'/core/SLIApi.php';
require_once SLI_WORK_DIR.'/core/SLITranslate.php';
require_once SLI_WORK_DIR.'/core/SLIAdmin.php';
require_once SLI_WORK_DIR.'/core/SLIVars.php';
require_once SLI_WORK_DIR.'/core/SLICache.php';
ob_start('SLITranslate::autoInit');

define('SLI_CURRENT_LANGUAGE', SLITranslate::getCurrentLanguage());
if (($langPos = strpos($_SERVER['REQUEST_URI'], SLI_CURRENT_LANGUAGE))!=false) {
    $systemUrl = str_replace('/'.SLI_CURRENT_LANGUAGE.'/', '/', $_SERVER['REQUEST_URI']);
    //Проверяем на наличие в базе разрешенных к отдаче контента
    if (SLITranslate::isAllowUrl($systemUrl)) {
        $_SERVER['REQUEST_URI'] = $systemUrl;
    }
}

//переопределяем функцию header (протестировать)
if (function_exists('runkit_function_rename')) {
    if (runkit_function_rename('header', 'SLIOriginalHeader')) {
      runkit_function_rename('sli_header', 'header');
    }
}

//в случае отключенного PECL runkit можно использовать данную функцию
if (!function_exists('sli_header')) {
    function sli_header($string, $replace = true , $http_response_code = null) {
    	//локализируем адрес переадресации        
    	$string = preg_replace('#((?:Refresh:.*url\s*=\s*)|(?:Location:\s*))((?:/(?!'.SLI_CURRENT_LANGUAGE.'/))|(?:http://'.preg_quote($_SERVER['HTTP_HOST']).'/(?!'.SLI_CURRENT_LANGUAGE.'/)))(.*)#Ui', '$1$2'.SLI_CURRENT_LANGUAGE.'/$3', $string);
    
    	$funcName = function_exists('SLIOriginalHeader') ? 'SLIOriginalHeader' : 'header';
    	
    	return $funcName($string, $replace, $http_response_code);
    }
}