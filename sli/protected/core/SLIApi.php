<?php
/**
 * SLIApi
 * Клас API
 * @author Ganjar@ukr.net
 */

class SLIApi {

    /**
     * Список языков для вывода на сайте
     * @var array
     */
    protected static $__languagesChangeList;

    /**
     * Получить список языковых версий сайта
     * @return array|null|string
     */
    public static function getLanguagesChangeList()
    {
        if (is_null(self::$__languagesChangeList)) {
            
            $requestUri = !empty($_SERVER['REQUEST_URI']) ? htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES) : '/';

            //Языки в админке
            $langList = SLISettings::getInstance()->getVar(SLISettings::LANGUAGES_VAR);

            //Выбранный язык
            $lang = SLITranslate::getCurrentLanguage();

            //Язык по умолчанию
            $defaultLangAlias = SLISettings::getInstance()->getVar(SLISettings::ORIGINAL_LANGUAGE_VAR);
            $allLanguages = SLISettings::getTranslateLanguages();
            if (!empty($allLanguages[$defaultLangAlias])) {

                array_unshift($langList, array(
                    'alias' =>  $defaultLangAlias,
                    'title' =>  $allLanguages[$defaultLangAlias],
                ));
            }

            //для авторизированных доступны все языки
            $isAuth = SLIAdmin::getUser();

            //Список языков на перевод
            foreach ($langList as $key=>&$val) {

                if (!$isAuth && isset($val['isActive']) && !$val['isActive']) {
                    unset($langList[$key]);
                    continue;
                }

                $val['href'] = ($val['alias']!=$defaultLangAlias ? '/'.$val['alias'] : '').$requestUri;
                $val['selected'] = $val['alias']==$lang || (!$lang && $val['alias']==$defaultLangAlias);
            }

            self::$__languagesChangeList = $langList;

            if (!is_array(self::$__languagesChangeList) || count(self::$__languagesChangeList)<2) {
                self::$__languagesChangeList = array();
            }
        }
        
        return self::$__languagesChangeList;
    }

    /**
     * Отобразить тег "игнорировать контент"
     * @param bool $print
     * @return string
     */
    public static function ignoreStart($print = true)
    {
        $tag = sprintf(SLITranslate::VAR_HTTP_PATTERN, 'ignore');
        if ($print) {
            echo $tag;
        } else {
            return $tag;
        }
    }

    /**
     * Отобразить закрывающий тег "игнорировать контент"
     * @param bool $print
     * @return string
     */
    public static function ignoreEnd($print = true)
    {
        $tag = sprintf(SLITranslate::VAR_HTTP_PATTERN, 'endIgnore');
        if ($print) {
            echo $tag;
        } else {
            return $tag;
        }
    }

    /**
     * Перевод фразы (Использовать только в случае если фраза не попадает на перевод обычным способом)
     * @param        $content
     * @param string $language
     * @return string
     */
    public static function t($content, $language = '')
    {
        return SLITranslate::t($content, $language);
    }

    /**
     * Проверить юзер агент на сканер SLI
     * @return bool
     */
    public static function isScannerBot()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT']=='SLI';
    }
}