<?php
/**
 * Данные страницы (подгружаются аяксом при подгрузке)
 * @author Ganjar@ukr.net
 */
?>

<?php $index = 0; foreach ($original as $id=>$value) {?>
	<?php SLIAdmin::renderPartial('parts/_translate_item', array('id' => $id, 'index' => $index, 'languages' => $languages, 'translate' => $translate, 'language' => $language, 'value' => $value,));?>
<?php $index++;}?>