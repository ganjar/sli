<?php
/**
 * JS подгрузки элементов при скролле
 * @author Ganjar@ukr.net
 */
?>

<?php
	//подготавливаем данные фильтра для ЖС
    $searchJS = ''; $count = 0; 
    if (!empty($search) && is_array($search)) {foreach ($search as $k=>$v) { $searchJS .= ($count ? ', ' : '')."$k:'".addslashes($v)."'"; $count++;}}
?>

<script>
	var params = {
        page: <?php echo $lastPage+1;?>, 
        getJson: true, 
        search: {<?php echo $searchJS;?>}
    },
	allPages = <?=$allPages?>,
	sendRequestNextPage = false,
	currentLanguage = $('#change-language').val(),
	originalLanguage = '<?php echo SLISettings::getInstance()->getVar('originalLanguage');?>',
	pageUrl = '<?=SLIAdmin::getUrl($_GET['action']);?>';

    $(function(){

        function scrollPagination() {

            var lastItemTop = SLItranslateApi.itemsBlock.height();

            //подгружаем след. страницу
            if (!sendRequestNextPage && params['page']<=allPages && lastItemTop && jQuery(window).scrollTop() > (lastItemTop-1000)) {
                sendRequestNextPage = true;
                site.setVisualLoad(true);
                jQuery.post(pageUrl, params, function(data){
                    if (data != null) {
                        params['page']++;

                        var newPage = jQuery(data.result).appendTo(SLItranslateApi.itemsBlock);

                        newPage.find('textarea').autoResize({
                            animate: false,
                            limit: 9999999
                        });

                        newPage.find('td.translate div').hide();
                        newPage.find('td.translate div.lang-'+currentLanguage).show();
                    }
                    sendRequestNextPage = false;
                    site.setVisualLoad();
                }, 'json');
            }
        }

        scrollPagination();

        jQuery(window).scroll(function(){ scrollPagination();});

        $('#change-language').change(function(){
            $('.languages .items td.translate div').hide();
            $('.languages .items td.translate div.lang-'+$(this).val()).show();

            if (typeof params=='object') {
                params.search.language = currentLanguage = $(this).val();
            }
        });

        SLItranslateApi.itemsBlock.on('click', '.icon-delete', function(){
            $(this).parent().find('.selectItem').click();
            $('#selectedDelete').click();
        });

        SLItranslateApi.itemsBlock.on('change', '.selectItem', function(){
            var contentBlock = $(this).parents('tr');
            if ($(this).is(':checked')) {
                contentBlock.addClass('delete');
            } else {
                contentBlock.removeClass('delete');
            }
        });
        $('input.selectItem').removeAttr('checked');
        
        $('.languages .items td.translate div').hide();
        $('.languages .items td.translate div.lang-'+currentLanguage).show();
    });
</script>