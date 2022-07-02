SLI устарел и больше не поддерживается.
Домен sli.su не продлевается и больше не имеет отношения к SLI.
===
SLI is deprecated and no longer supported. The sli.su domain is not renewed and is no longer associated with SLI.
===
SLI - Site Language Injection  <br>
Make a website multilingualfor 5 minutes  <br>
Мультиязычный сайт на php за 5 минут  <br>
<br>
Требования:<br>
Apache, PHP 5.2 и выше, mod_rewrite, MySQL, Memcache (для быстрой работы приложения. Не обязательно), PDO, mbString, SimpleXML<br>
<br>
Установка:  <br>
1.  Залить файлы на сервер<br>
2.  Добавить в файл .htaccess который находится в корневой директории сайта строку<br>
    php_value auto_prepend_file "[full_path]/protected/sli.php"<br>
    Где [full_path] - полный пусть к директории с модулем.     <br>
    Если весь сайт работает через один контроллер - можно подключить модуль через include "[full_path]/protected/sli.php";<br>
3.  Выставить права 0777 на папку protected/data и на все внутренние директории<br>
4.  Открыть файл protected/config/config.php и прописать данные конекта к БД MySQL <br>
5.  Зайти в админку по адресу http://адресСайта/sli/index.php<br>
6.  Ввести свой Email и пароль.<br>
7.  Войти под указанными данными<br>
<br>
