<?php
/**
 * Шаблон вывода оригинальной фразы и всех вариантов на перевод
 * @author Ganjar@ukr.net
 */
?>

<tr id="<?php echo $id;?>">
    <td class="idColumn"><?php echo $id;?></td>
    <td class="original"><?php echo $value;?></td>
    <td class="translate">
    <?php foreach ($languages as $langKey=>$langValue) {?>
		<div class="lang-container lang-<?php echo $langValue['alias'];?>" style="<?php echo $langValue['alias']!=$language['alias'] ? 'display:none;' : '';?>">
			<textarea class="form-control" name="<?php echo $id;?>" lang="<?php echo $langValue['alias'];?>"><?php echo !empty($translate[$langValue['alias']][$id]) ? $translate[$langValue['alias']][$id] : '';?></textarea>
			<?php if (SLISettings::getInstance()->getVar('originalLanguage')) {?>
			<a href="#" type="button" onclick="SLItranslateApi.autoTranslate(this);return false;" class="auto-translate" data-toggle="tooltip" title="Машинный перевод">
                <span class="glyphicon glyphicon-cloud"></span>
            </a>
			<?php }?>
		</div>
    <?php }?>
    </td>
    <td class="button-column">
    	<label class="cmsButtonHolder">
			<a href="javascript:void(0);" title="Удалить" class="glyphicon glyphicon-remove icon-delete"></a>
			<input type="checkbox" class="selectItem" />
    	</label>
    </td>
</tr>