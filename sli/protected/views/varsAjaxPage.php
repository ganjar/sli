<?php
/**
 * Данные страницы переменных (подгружаются аяксом при подгрузке)
 * @author Ganjar@ukr.net
 */
?>

<?php $index = 0; foreach ($original as $id=>$value) {?>
	<?php SLIAdmin::renderPartial('parts/_var_item', array('id' => $id, 'index' => $index, 'languages' => $languages, 'translate' => $translate, 'language' => $language, 'value' => $value,));?>
<?php $index++;}?>