<?php

/**
 * SLISettings
 * Класс работы с настройками переводчика
 * @author Ganjar@ukr.net
 */
if (!defined('SLI_WORK_DIR')) { die('SLI_WORK_DIR is not defined');}
define('SLI_SETTINGS_FILE', SLI_WORK_DIR.'/data/settings.dat');

class SLISettings {

    const LANGUAGES_VAR  = 'languages';
    const ORIGINAL_LANGUAGE_VAR  = 'originalLanguage';

    /**
     * Вектор настроек
     */
    private static $_settings;
    
    /**
     * Нужно ли перезаписывать файл настроек
     */
    private static $_mustSave = false;
    
    private static $_instance;        
    
    /**
     * Инициализация
     */
    public function __construct()
    {
        if (file_exists(SLI_SETTINGS_FILE)) {
            self::$_settings = unserialize(file_get_contents(SLI_SETTINGS_FILE));   
        }
    }    
    
    /**
     * Получить копию объекта
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new SLISettings;
        }
        
        return self::$_instance;
    }
    
    /**
     * Получить переменную
     */    
    public function getVar($var, $htmlencode = false)
    {
        $result = !empty(self::$_settings[$var]) ? self::$_settings[$var] : null;
        
        if ($htmlencode) {
        	$result = htmlspecialchars_decode($result, ENT_QUOTES);
        }
        
        return $result;
    }
    
    /**
     * Сохранить переменную
     */
    public function setVar($name, $value)
    {
        if (is_string($value)) {
            if ($name=='translateKey') {
                $value = preg_replace('#[^a-z0-9\.\-_]#i', '', $value);
            }
            $value = htmlspecialchars(stripcslashes($value), ENT_QUOTES, 'utf-8');
        }
        
        if ((!self::$_mustSave && isset(self::$_settings[$name]) && self::$_settings[$name]!=$value) || !isset(self::$_settings[$name])) {
            self::$_mustSave = true;
        }
        self::$_settings[$name] = $value;
    }
    
    /**
     * Получить список основных атрибутов
     */
    public static function getAttributes()
    {
        return array(
            'languages'         => array(
                'title' => 'Языки',
                'help'  => '',
            ),
            'originalLanguage' => array(
                'title' => 'Язык оригинала',
                'help'  => '',
            ),
            'translateKey' => array(
                'title' => 'API Ключ',
                'help'  => 'для работы авто перевода нужно получить Yandex ключ',
            ),
            'ignoreTags'        => array(
                'title' => 'Игнорировать теги', 
                'help'  => 'Список тегов наполнение которых будет игнорироваться. Возможно использование регулярного выражения',
            ),
            /*'ignoreAttr'        => array(
                'title' => 'Игнор тегов с указанным атрибутом',
                'help'  => 'Можно указать как 1 атрибут так и атрибут со значением',
            ),*/
            'visibleAttr'       => array(
                'title' => 'Переводить значение атрибутов',
                'help'  => 'Список html атрибутов наполнение которых будет обработано переводчиком',
            ),
            'ignorePageVsContent'	=> array(
                'title' => 'Игнорировать страницы с текстом', 
                'help'  => 'Игнорировать страницы на которых присутствует указанный текст
Каждое значение с новой строки
Для использования регулярных выражений - строка должна начинаться с символа \'#\'',
            ),
            'igroreUrls'        => array(
                'title' => 'Игнорировать адреса', 
                'help'  => "Список URL адресов для которых не будет запущен парсер.
Каждый адрес должен начинаться с символа '/' например /news/
Для использования регулярных выражений - строка должна начинаться с символа '#' 
например #/news/.* - отключит парсер для всех адресов начинающихся на /news/",
            ),
            'allowUrls'        => array(
                'title' => 'Разрешенные адреса',
                'help'  => "Список разрешенных к выдаче URL адресов.
Если указан хотя бы один адрес - все остальные ссылки будут игнорироватся.",
            ),
            'showRuntime'       => array(
                'title' => 'Показывать время отработки программы',
                'help'  => '',
            ),
            'cacheStatus'       => array(
                'title' => 'Кэшировать результаты работы парсера', 
                'help'  => '',
            ),
            'cacheTime'  	    => array(
                'title' => 'Время кэширования (в секундах)',
                'help'  => '',
            ),
        );   
    }

    /**
     * Получить список языков используемых для автоперевода
     * @return array
     */
    public static function getTranslateLanguages()
    {
        return array(
            '' => 'Выберите язык',
            'ru' => 'Русский',
            'en' => 'Английский',
            'fr' => 'Французский',
            'sq' => 'Албанский',
            'ar' => 'Арабский',
            'bg' => 'Болгарский',
            'hu' => 'Венгерский',
            'vi' => 'Вьетнамский',
            'gl' => 'Галицийский',
            'nl' => 'Голландский',
            'el' => 'Греческий',
            'da' => 'Датский',
            'iw' => 'Иврит',
            'id' => 'Индонезийский',
            'es' => 'Испанский',
            'it' => 'Итальянский',
            'ca' => 'Каталанский',
            'zh-CN' => 'Китайский упрощенный',
            'zh' => 'Китайский',
            'zh-TW' => 'Китайский традиционный',
            'ko' => 'Корейский',
            'lv' => 'Латышский',
            'lt' => 'Литовский',
            'mt' => 'Мальтийский',
            'de' => 'Немецкий',
            'no' => 'Норвежский',
            'fa' => 'Персидский',
            'pl' => 'Польский',
            'pt' => 'Португальский',
            'ro' => 'Румынский',
            'sr' => 'Сербский',
            'sk' => 'Словацкий',
            'sl' => 'Словенский',
            'tl' => 'Тагальский',
            'th' => 'Тайский',
            'tr' => 'Турецкий',
            'uk' => 'Украинский',
            'fi' => 'Финский',
            'hi' => 'Хинди',
            'hr' => 'Хорватский',
            'cs' => 'Чешский',
            'sv' => 'Шведский',
            'et' => 'Эстонский',
            'ja' => 'Японский',
        );
    }
    
    /**
     * Получить указанный атрибут
     */
    public static function getAttribute($var)
    {
        $attributes = self::getAttributes();
        return !empty($attributes[$var]) ? (object)$attributes[$var] : null;
    }

    /**
     * Обезательные действия при уничтожении класса 
     */        
    function __destruct()
    {
        if (isset(self::$_mustSave) && self::$_mustSave) {
            file_put_contents(SLI_SETTINGS_FILE, serialize(self::$_settings));
        }
    } 
}