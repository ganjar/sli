<?php
/**
 * Шаблон регистрации
 * @author Ganjar@ukr.net
 */
?>

<?php if (!SLICore::getInstance()->db()) {?>
<div class="bs-callout bs-callout-danger">
    <h4>Не установлено соеденение с MySQL</h4>
    <p>Откройте файл sli/protected/config/config.php и настройке доступ к базе данных.</p>
</div>
<?php }?>

<form class="form-signin" id="search-form" action="?" method="POST">
    <h2 class="form-signin-heading">Регистрация</h2>

    <input name="LoginForm[login]" type="text" class="form-control" placeholder="Email" autofocus>
    <?php if (!empty($errors['login'])) {?><div class="errorMessage"><?php echo $errors['login'];?></div><?php }?>

    <input name="LoginForm[password]" type="password" class="form-control" placeholder="Пароль">
    <?php if (!empty($errors['password'])) {?><div class="errorMessage"><?php echo $errors['password'];?></div><?php }?>

    <input type="password" id="LoginForm_password" name="LoginForm[applypassword]" class="form-control" placeholder="Повторите пароль" required="required">
    <?php if (!empty($errors['applypassword'])) {?><div class="errorMessage"><?php echo $errors['applypassword'];?></div><?php }?>

    <button class="btn btn-lg btn-primary btn-block" type="submit">Сохранить</button>
</form>