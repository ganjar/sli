/******************SLI for Yii2******************/
Текущая версия: 1.0 (Проверить обновления - http://sli.su/download/)
Лицензия: Donationware 
Автор: Богдан Рыхаль (GANJAR) 
Сайт: http://sli.su/

Продажа и публикация программы без согласия
автора - запрещена.
По всем вопросам пишите на Email: support@sli.su 

/******************Требования************/
Apache, PHP 5.2 и выше, mod_rewrite, MySQL, Memcache (для быстрой работы приложения. Не обязательно), PDO, mbString, SimpleXML

/******************Установка************/                         
Установка:
1.  Залить файлы на сервер
2.  Добавить в файл .htaccess который находится в корневой директории сайта строку
    php_value auto_prepend_file "[full_path]/protected/sli.php"
    Где [full_path] - полный пусть к директории с модулем.     
    Если весь сайт работает через один контроллер - можно подключить модуль через include "[full_path]/protected/sli.php";
3.  Выставить права 0777 на папку protected/data и на все внутренние директории
4.  Открыть файл protected/config/config.php и прописать данные конекта к БД MySQL 
5.  Зайти в админку по адресу htt://адресСайта/sli/index.php
6.  Ввести свой Email и пароль.
7.  Войти под указанными данными

Больше информации в разделе документация:
http://sli.su/documentation/

Одним из условий правильной работы системы является правильная настройка RewriteEngine в файле .htaccess
Пример такой настройки вы можете посмотреть в папке sli_demo/.htaccess
Строка
RewriteRule ^(.*)$ index.php [L]
отвечает за перенаправление всех запросов на файл index.php
Если у Вас сайт работает не через 1 главный контроллер, вы можете самостоятельно переписать правила реврайта для правильной работы языковых версий.

Игнор всего что находитя внутри
<!--SLI::ignore-->
Контент
<!--SLI::endIgnore-->

Или
<?php SLIApi::ignoreStart();?>
Контент
<?php SLIApi::ignoreEnd();?>

Быстрый вывод языков на сайте: добавьте в html код
<!--SLIApi::getLanguagesChangeList-->
Либо через php api:
SLIApi::getLanguagesChangeList() - массив с языками
Использование:
<ul class="language">
    <?php foreach(SLIApi::getLanguagesChangeList() as $val) {?>
    <li<?php echo $val['selected'] ? ' class="selected"': '';?>><a % href="<?php echo $val['href'];?>"><img src="/sli/static/img/flags/<?=$val['alias']?>.png" alt="<?=$val['title']?>" title="<?=$val['title']?>"></a></li>
    <?php }?>
</ul>