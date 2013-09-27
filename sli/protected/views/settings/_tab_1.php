<?php
/**
 * Шаблон языковых настроек
 * @author Ganjar@ukr.net
 */
?>

<div class="help">
    * - Поля обезательные к заполнению<br/>
</div>

<div class="languages col-md-12">
    <div class="row">
    <?php $count = 0; $languages = $settings->getVar(SLISettings::LANGUAGES_VAR); if ($languages) {foreach ($languages as $id=>$value) { $class = !($count%2) ? 'grey' : '';?>
        <div class="col-md-5 language-item">
            <div class="form-group row">
                <label class="col-md-4 control-label">
                    Название *
                </label>
                <div class="col-md-8">
                    <input class="form-control" type="text" value="<?php echo $value['title'];?>" name="settings[<?php echo SLISettings::LANGUAGES_VAR;?>][<?php echo $id;?>][title]" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4 control-label">
                    Активность
                </label>
                <div class="col-md-8">
                    <select class="form-control" name="settings[<?php echo SLISettings::LANGUAGES_VAR;?>][<?php echo $id;?>][isActive]" title="Неактивные языки доступны только для пользователей админки" data-toggle="tooltip">
                        <?php echo SLIAdmin::getHtmlOptions(array(1 => 'Да', 0 => 'Нет',), $value['isActive']);?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4 control-label">Алиас *</label>
                <div class="col-md-8">
                    <input class="form-control" type="text" value="<?php echo $value['alias'];?>" name="settings[<?php echo SLISettings::LANGUAGES_VAR;?>][<?php echo $id;?>][alias]"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4 control-label">Алиас для автопереводчика</label>
                <div class="col-md-8">
                    <select class="form-control" name="settings[<?php echo SLISettings::LANGUAGES_VAR;?>][<?php echo $id;?>][autoTranslateAlias]">
                        <?php echo SLIAdmin::getTranslateLanguagesHtmlOptions($value['autoTranslateAlias']);?>
                    </select>
                </div>
            </div>
        </div>
    <?php $count++;}}?>
    </div>
</div>

<button type="button" class="btn btn-success add-lang" id="add-language"><span class="glyphicon glyphicon-plus"></span>Добавить</button>
<script type="text/javascript">
	var translateLanguagesHtmlOptions = '<?php echo SLIAdmin::getTranslateLanguagesHtmlOptions();?>';
    var count = <?php echo !empty($count) ? $count : 0;?>,
    index = <?php echo !empty($id) ? $id : 0;?>,
    languagesVar = '<?php echo SLISettings::LANGUAGES_VAR;?>';
    var languagesContainer = $('.languages > .row');
    $('#add-language').click(function(){
        index++;
        languagesContainer.append(
            '<div class="col-md-5 language-item">' +
                '<div class="form-group row">' +
                    '<label class="col-md-4 control-label">Название *</label>' +
                    '<div class="col-md-8">' +
                        '<input class="form-control" type="text" value="" name="settings['+languagesVar+']['+index+'][title]">' +
                    '</div>' +
                '</div>' +
                '<div class="form-group row">' +
                    '<label class="col-md-4 control-label">Активность</label>' +
                    '<div class="col-md-8">' +
                        '<select class="form-control" name="settings['+languagesVar+']['+index+'][isActive]" title="Неактивные языки доступны только для пользователей админки" data-toggle="tooltip">' +
                            '<option selected="selected" value="1">Да</option><option value="0">Нет</option>' +
                        '</select>' +
                    '</div>' +
                '</div>' +
                '<div class="form-group row">' +
                    '<label class="col-md-4 control-label">Алиас *</label>' +
                    '<div class="col-md-8">' +
                        '<input class="form-control" type="text" value="" name="settings['+languagesVar+']['+index+'][alias]">' +
                    '</div>' +
                '</div>' +
                '<div class="form-group row">' +
                    '<label class="col-md-4 control-label">Алиас для автопереводчика</label>' +
                    '<div class="col-md-8">' +
                        '<select class="form-control" name="settings['+languagesVar+']['+index+'][autoTranslateAlias]">'+translateLanguagesHtmlOptions+'</select>' +
                    '</div>' +
                '</div>' +
            '</div>');
        count++;
    });
</script>