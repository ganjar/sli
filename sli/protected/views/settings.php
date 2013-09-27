<?php
/**
 * Шаблон настроек
 * @author Ganjar@ukr.net
 */
?>

<div class="settings-page">
    <form method="post" action="<?php echo SLIAdmin::getUrl('settings');?>" id="settings-form">

        <ul class="nav nav-tabs" id="settingsTab">
            <li class="active"><a href="#tab_1" data-toggle="tab">Языки</a></li>
            <li><a href="#tab_2" data-toggle="tab">Параметры</a></li>
            <li><a href="#tab_3" data-toggle="tab">Пользователи админки</a></li>
        </ul>

        <div class="tab-content">
            <div id="tab_1" class="tab-pane active">
                <?php SLIAdmin::renderPartial('settings/_tab_1', array('settings' => $settings,));?>
            </div>

            <div id="tab_2" class="tab-pane">
                <?php SLIAdmin::renderPartial('settings/_tab_2', array('settings' => $settings,));?>
            </div>

            <div id="tab_3" class="tab-pane">
                <?php SLIAdmin::renderPartial('settings/_tab_3', array('settings' => $settings, 'admin' => $admin,));?>
            </div>

            <div class="buttons submit buttons">
                <button name="yt0" type="submit" class="btn btn-primary"><span class="save">Сохранить</span></button>
            </div>
        </div>

    </form>
</div>