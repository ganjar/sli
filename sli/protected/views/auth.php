<?php
/**
 * Шаблон авторизации
 * @author Ganjar@ukr.net
 */
?>

<form class="form-signin" id="search-form" action="?" method="POST">
    <h2 class="form-signin-heading">Авторизация</h2>
    <input name="LoginForm[login]" type="text" class="form-control" placeholder="Email address" autofocus>
    <input name="LoginForm[password]" type="password" class="form-control" placeholder="Password">
    <!--<label class="checkbox">
        <input type="checkbox" value="remember-me"> Запомнить
    </label>-->
    <button class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
</form>