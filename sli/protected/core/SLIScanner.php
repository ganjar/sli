<?php
/**
 * SLIScanner
 * Клас серфинга по страницам сайта для занисения контента в переводчик
 * @author Ganjar@ukr.net
 */

ini_set('max_execution_time', 10000000);

if (!defined('SLI_WORK_DIR')) { die('SLI_WORK_DIR is not defined');}
define('SLI_SCANNER_FILE', SLI_WORK_DIR.'/data/scanner.dat');

class SLIScanner {

    /**
     * Срок действия временного файла
     */
    const TIME_ACTIONS = 172800;

    /**
     * Вектор уже отсканированых адресов
     * @var array
     */
    protected static $_parseUrl = array();

    /**
     * Получить список отсканированных страниц
     * @return array|mixed
     */
    public static function getParseUrl()
    {       
        if (empty(self::$_parseUrl) && is_readable(SLI_SCANNER_FILE) && filemtime(SLI_SCANNER_FILE)>time()-self::TIME_ACTIONS) {
            self::$_parseUrl = @unserialize(file_get_contents(SLI_SCANNER_FILE));
        }

        return self::$_parseUrl;
    }

    /**
     * Сканировать страницы
     * @param array $options
     */
    public static function run($options = array())
    {
        $type   = !empty($options['type']) ? $options['type'] : 1;
        $url    = !empty($options['url']) ? $options['url'] : '/';
        $last   = !empty($options['last']) ? $options['last'] : false;

        //если нужно продолжить с последней отсканированной страницы
        $lastUrl = '';
        if ($last) {
            
            //получаем список отсканеных страниц
            self::getParseUrl();

            if (!empty(self::$_parseUrl) && is_array(self::$_parseUrl)) {
                $urls = self::$_parseUrl;
                $lastUrl = array_pop($urls);
            }
        }

        switch ($type) {
            //Парсинг по URL
            case 1:
                $urls = array($lastUrl ? $lastUrl : $url);
                self::parseFromUrl($urls);
                break;

            //Парсинг по sitemap.xml
            case 2:
                self::parseFromSiteMap($url, $lastUrl);
                break;
        }
    }

    /**
     * Сканировать сайт по заданному начальному url
     * @param array $urls
     */
    public static function parseFromUrl($urls = array())
    {
        while (is_array($urls) && count($urls)) {

            foreach ($urls as $key=>$url) {

                if (!isset(self::$_parseUrl[$url])) {

                    self::$_parseUrl[$url] = $url;

                    //Сохраняем спиок просмотренных URL
                    file_put_contents(SLI_SCANNER_FILE, serialize(self::$_parseUrl));

                    echo 'Scan url: '.$url.'<br/>';
                    $content = self::getContent($url);

                    if ($content) {

                        ob_end_flush();

                        preg_match_all('#href\s*=\s*(?:"|\')((?:http://'.preg_quote($_SERVER['HTTP_HOST']).')|/.+)(?:"|\')#Ui', $content, $match);
                        $urls = array_merge($urls, $match[1]);
                        unset($match);
                    }
                }

                unset($urls[$key]);
            }

        }
    }

    /**
     * Сканировать страницы сайта по файлу sitemap
     * @param string $siteMapFile
     * @param string $lastUrl
     */
    public static function parseFromSiteMap($siteMapFile, $lastUrl = '')
    {
        $startLast = false;
        $siteMap = self::getSiteMapUrls($siteMapFile);

        foreach ($siteMap as $parseUrl) {

            $parseUrl = preg_replace('#http://.*/#Ui', '/', $parseUrl);

            //Продолжить с последнего
            if ($lastUrl && !$startLast && $parseUrl!=$lastUrl) {
                continue;
            } elseif ($lastUrl && $parseUrl==$lastUrl) {
                $startLast = true;
            }

            if (!isset(self::$_parseUrl[$parseUrl])) {

                self::$_parseUrl[$parseUrl] = $parseUrl;

                //Сохраняем спиок просмотренных URL
                file_put_contents(SLI_SCANNER_FILE, serialize(self::$_parseUrl));

                echo 'Scan url: '.$parseUrl.'<br/>';
                self::getContent($parseUrl);
                ob_end_flush();
            }
        }
    }

    /**
     * Получить список url из файла sitemap
     * @param string $siteMapFile
     * @return array
     */
    public static function getSiteMapUrls($siteMapFile)
    {
        $result = array();

        $siteMap = simplexml_load_string(self::getContent($siteMapFile));
        if ($siteMap===false) {
            echo 'Error while parsing the document';
        } else {
            if (isset($siteMap->url)) {

                foreach ($siteMap->url as $item) {
                    $item = (array)$item;
                    if (!isset($item['loc'])) { continue;}
                    $result[] = $item['loc'];
                }
            }
        }

        return $result;
    }

    /**
     * Получить контент страницы
     * @param $url
     * @return string
     */
    public static function getContent($url)
    {
        if (is_string($url) && $url[0]=='/') {

            
            $url = 'http://'.$_SERVER['HTTP_HOST'].$url;

            return @file_get_contents($url);
        }
    }

    /**
     * Получить список основных аттрибутов
     * @return array
     */
    public static function getAttributes()
    {
        return array(
            'type'   => array(
                'title' => 'Тип сканирования',
                'help'  => '',
            ),
            'url'   => array(
                'title' => 'Адрес начала сканирования или путь к файлу sitemap.xml',
                'help'  => 'Например /contacts/ для того что бы начать сканировать с раздела контактов.
Внимание! Полное сканирование может занять длительное время. Не закрывайте в этот момент страницу.',
            ),
            'last'  => array(
                'title' => 'Продолжить с последнего отсканированного адреса',
                'help'  => '',
            ),
        );   
    }

    /**
     * Получить возможные типы сканирования сайта
     * @return array
     */
    public static function getScanTypes()
    {
        return array(
            1   => 'По URL',
            2   => 'Sitemap',
        );
    }

    /**
     * Получить указанный аттрибут
     * @param $var
     * @return null|object
     */
    public static function getAttribute($var)
    {
        $result = null;
        $attributes = self::getAttributes();

        if (!empty($attributes[$var])) {
            $result = $attributes[$var];
            $result['value'] = !empty($_POST['scanner']) && !empty($_POST['scanner'][$var]) ? $_POST['scanner'][$var] : null;
            $result = (object)$result;
        }

        return $result;
    }

}