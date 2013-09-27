<?php
/**
 * Шаблон управления пользователями админки
 * @author Ganjar@ukr.net
 */
?>

<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-primary">
            <div class="panel-heading">Главный админ</div>
            <div class="panel-body">
                <div class="form-group row">
                    <label class="col-md-4 control-label">E-mail</label>
                    <div class="col-md-8">
                        <input class="form-control" type="text" value="<?php echo $admin['login'];?>" name="settings[admin][login]" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 control-label">
                        Пароль
                    </label>
                    <div class="col-md-8">
                        <input class="form-control" type="password" value="" name="settings[admin][password]" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 control-label">
                        Подтверждение пароля
                    </label>
                    <div class="col-md-8">
                        <input class="form-control" type="password" value="" name="settings[admin][applypassword]" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 control-label">
                        Старый пароль
                    </label>
                    <div class="col-md-8">
                        <input class="form-control" type="password" value="" name="settings[admin][old_password]" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
