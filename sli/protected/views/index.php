<?php
/**
 * Шаблон работы с переводами
 * @author Ganjar@ukr.net
 */
?>


<?php SLIAdmin::renderPartial('parts/_filter_form', array('languages' => $languages, 'search' => $search, 'language' => $language,));?>

<?php if ($showSearch) {?>
    <script type="text/javascript">$(function(){$('#showFilter').click();});</script>
<?php }?>


<?php if (!empty($original) && is_array($original)) {?>
    <div class="languages">
        <table class="table table-striped items" id="items">
            <tbody>
            <?php $index = 0; foreach ($original as $id=>$value) {?>
                <?php SLIAdmin::renderPartial('parts/_translate_item', array('id' => $id, 'index' => $index, 'languages' => $languages, 'translate' => $translate, 'language' => $language, 'value' => $value,));?>
                <?php $index++;}?>
            </tbody>
        </table>
    </div>
<?php } else {?>
    <div class="container">
        <div class="empty-result">Нет элементов для отображения</div>
    </div>
<?php }?>


<?php SLIAdmin::renderPartial('parts/_scroll_pager', array('search' => $search, 'allPages' => $allPages, 'lastPage' => $lastPage,));?>

<script type="text/javascript">
    $(function(){
        SLItranslateApi.itemsBlock.on('change', 'textarea', function(){
            var currId = $(this).attr('name');
            if (currId) {
                site.setVisualLoad(true);
                $.post('?action=saveTranslate', {language: $(this).attr('lang'), idContent: currId, content: $(this).val()}, function(){
                    site.setVisualLoad();
                });
            }
        });

        $('#selectedDelete').click(function(){
            var items = SLItranslateApi.itemsBlock.find('td.button-column input.selectItem:checked'),
                countItems = items.length;

            if (countItems && confirm('Удалить выбранные элементы? ('+countItems+'шт.)')) {
                var deleteIds = [];
                items.each(function(){
                    itemId = $(this).parents('tr').attr('id');
                    deleteIds.push(itemId);
                });
                site.setVisualLoad(true);
                $.post('?action=deleteTranslate', {idContent: deleteIds}, function(data){
                    site.setVisualLoad();
                    if (data) {
                        window.location.reload();
                    } else {
                        alert('Ошибка! не возможно удалить выбранные элементы.');
                    }
                    site.setVisualLoad();
                }, 'json');
            }
        });
    });
</script>