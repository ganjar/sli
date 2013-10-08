var SLItranslateApi = {
    filter: null
    , extFilter: null
    , itemsBlock: null

    ,translate: function (text, sl, tl, success){
        $.ajax({ url: 'http://translate.yandex.ru/tr.json/translate?srv=tr-text&id=812c6278-0-0&reason=auto',
            dataType: 'jsonp',
            data: { text: text, lang: sl+'-'+tl },
            success: function(result) { success(result); },
            error: function(XMLHttpRequest, errorMsg, errorThrown) { alert(errorMsg); }
        });
    }

    ,autoTranslate: function (elem) {
        var selectedLang = $('#change-language option:selected');
        var translateAlias = selectedLang.attr('talias') ? selectedLang.attr('talias') : selectedLang.val();

        var textArea = $(elem).parent().find('textarea');
        var originalTd = $(elem).parents('tr').find('td.original');
        var originalText = originalTd.find('textarea').length ? originalTd.find('textarea').val() : originalTd.text();

        if (textArea.val()=='' || confirm('Поле перевода не пустое. Заменить его автопереводом?')) {

            site.setVisualLoad(true);

            SLItranslateApi.translate(originalText, originalLanguage, translateAlias, function(response){
                textArea.val(response).change();
                site.setVisualLoad();
            });
        }
    }

    ,translateAll: function () {
        var selectedLang = $('#change-language option:selected');
        var translateAlias = selectedLang.val();
        $('.lang-'+translateAlias+' textarea[name!=""]:empty').each(function(){
            if ($(this).val()=='') {
                $(this).parent().find('.auto-translate').click();
            }
        });
    }

    , initFilterAffix: function() {
        SLItranslateApi.filter.affix({
            offset: {
                top: $('#filter-cap').offset().top
            }
        });
    }
};

$(function(){

    SLItranslateApi.itemsBlock = $('#items');

    SLItranslateApi.filter = $('#filters');

    if (!SLItranslateApi.filter.length) {
        return false;
    }

    SLItranslateApi.extFilter = SLItranslateApi.filter.find('tbody');

    SLItranslateApi.initFilterAffix();

    $('#showFilter').click(function() {
        SLItranslateApi.extFilter.toggle();
        return false;
    });

    //определяем высоту инпута для перевода
    $('textarea').autoResize({
        animate: false,
        limit: 9999999
    });
});