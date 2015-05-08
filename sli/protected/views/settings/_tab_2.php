<?php
/**
 * Шаблон параметров переводчика
 * @author Ganjar@ukr.net
 */
?>

<div class="row">

    <div class="col-lg-6">
        <div class="panel panel-primary">
            <div class="panel-heading">Настройка парсера</div>
            <div class="panel-body">

                <div class="form-group row">
                    <label class="col-md-4 control-label">
                        <?php echo SLISettings::getAttribute('igroreUrls')->title;?><br>
                        (каждый адрес с новой строки)
                    </label>
                    <div class="col-md-8">
                        <textarea name="settings[igroreUrls]" class="form-control" title="<?php echo SLISettings::getAttribute('igroreUrls')->help;?>" data-toggle="tooltip"><?php echo $settings->getVar('igroreUrls');?></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 control-label">
                        <?php echo SLISettings::getAttribute('allowUrls')->title;?>
                        <br>
                        (каждый адрес с новой строки)
                    </label>
                    <div class="col-md-8">
                        <textarea name="settings[allowUrls]" class="form-control" title="<?php echo SLISettings::getAttribute('allowUrls')->help;?>" data-toggle="tooltip"><?php echo $settings->getVar('allowUrls');?></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 control-label">
                        <?php echo SLISettings::getAttribute('ignorePageVsContent')->title;?>
                        <br>
                        (каждый адрес с новой строки)
                    </label>
                    <div class="col-md-8">
                        <textarea name="settings[ignorePageVsContent]" class="form-control" title="<?php echo SLISettings::getAttribute('ignorePageVsContent')->help;?>" data-toggle="tooltip"><?php echo $settings->getVar('ignorePageVsContent');?></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 control-label">
                        <?php echo SLISettings::getAttribute('ignoreTags')->title;?>
                        <br>
                        (через запятую)
                    </label>
                    <div class="col-md-8">
                        <textarea name="settings[ignoreTags]" class="form-control" title="<?php echo SLISettings::getAttribute('ignoreTags')->help;?>" data-toggle="tooltip"><?php echo $settings->getVar('ignoreTags');?></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 control-label">
                        <?php echo SLISettings::getAttribute('visibleAttr')->title;?>
                        <br>
                        (через запятую)
                    </label>
                    <div class="col-md-8">
                        <textarea class="form-control" name="settings[visibleAttr]" title="<?php echo SLISettings::getAttribute('visibleAttr')->help;?>"  data-toggle="tooltip"><?php echo $settings->getVar('visibleAttr');?></textarea>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="panel panel-primary">
            <div class="panel-heading">Настройки автопереводчика</div>
            <div class="panel-body">
                <div class="form-group row">
                    <label class="col-md-4 control-label"><?php echo SLISettings::getAttribute('originalLanguage')->title;?></label>
                    <div class="col-md-8">
                        <select name="settings[originalLanguage]" class="form-control">
                            <?php echo SLIAdmin::getTranslateLanguagesHtmlOptions($settings->getVar('originalLanguage'));?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 control-label">
                        <?php echo SLISettings::getAttribute('translateKey')->title;?>
                        (<a href="https://tech.yandex.ru/keys/get/?service=trnsl" target="_blank">Получить ключ</a>)
                    </label>
                    <div class="col-md-8">
                        <input type="text" name="settings[translateKey]" value="<?php echo $settings->getVar('translateKey');?>" class="form-control"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="panel panel-primary">
            <div class="panel-heading">Настройки кэширования</div>
            <div class="panel-body">
                <div class="form-group row">
                    <label class="col-md-4 control-label"><?php echo SLISettings::getAttribute('cacheStatus')->title;?></label>
                    <div class="col-md-8">
                        <select class="form-control" name="settings[cacheStatus]">
                            <?php echo SLIAdmin::getHtmlOptions(array(1 => 'Да', 0 => 'Нет',), $settings->getVar('cacheStatus'));?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 control-label"><?php echo SLISettings::getAttribute('cacheTime')->title;?></label>
                    <div class="col-md-8">
                        <input class="form-control" type="text" name="settings[cacheTime]" value="<?php echo $settings->getVar('cacheTime');?>"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="panel panel-primary">
            <div class="panel-heading">Дополнительные настройки</div>
            <div class="panel-body">
                <div class="form-group row">
                    <label class="col-md-4 control-label"><?php echo SLISettings::getAttribute('showRuntime')->title;?></label>
                    <div class="col-md-8">
                        <select class="form-control" name="settings[showRuntime]">
                            <?php echo SLIAdmin::getHtmlOptions(array(1 => 'Да', 0 => 'Нет',), $settings->getVar('showRuntime'));?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>