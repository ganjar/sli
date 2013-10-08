<?php
/**
 * Шаблон фильтра
 * @author Ganjar@ukr.net
 */
?>

<div id="filter-cap"></div>
<div id="filters">
    <form class="form-inline" id="search-form" action="<?php echo SLIAdmin::getUrl($_GET['action']);?>" method="GET">
        <input type="hidden" name="action" value="<?php echo $_GET['action'];?>"/>

        <table class="table items filters search">
            <thead>
            <tr>
                <td class="idColumn">
                    ID
                </td>
                <td class="original">
                    Оригинал
                </td>
                <td class="translate">
                    <div class="s-translate-container">
                        <?php if (SLISettings::getInstance()->getVar('originalLanguage')) {?>
                            <a href="#" type="button" onclick="if (confirm('Перевести все пустые элементы?')) { SLItranslateApi.translateAll();} return false;" class="translate-all pull-right" data-toggle="tooltip" data-placement="bottom"
                               title="Заполнить все пустые элементы на странице машинным переводом">
                                <span class="glyphicon glyphicon-cloud"></span>
                            </a>
                        <?php }?>
                        <select id="change-language" name="search[language]" class="col-lg-2 form-control">
                            <?php foreach ($languages as $langKey=>$langValue) {?>
                                <option talias="<?php echo $langValue['autoTranslateAlias'];?>" value="<?php echo $langValue['alias'];?>"<?php echo $langValue['alias']==$language['alias'] ? 'selected="selected"' : '';?>><?php echo $langValue['title'];?></option>
                            <?php }?>
                        </select>
                    </div>
                </td>
                <td>
                    <!--<a href="#" onclick="$('html, body').animate({scrollTop: 0}, 1000); return false;" data-toggle="tooltip" title="Вверх">
                        <span class="glyphicon glyphicon-arrow-up"></span>
                    </a>-->

                    <a class="icon-delete" href="javascript:void(0);" id="selectedDelete" data-toggle="tooltip" title="Удалить выбранные">
                        <span class="glyphicon glyphicon-remove"></span>
                    </a>

                    <a class="toggle" href="#" id="showFilter" data-toggle="tooltip" title="Показать/Скрыть фильтр">
                        <span class="glyphicon glyphicon-align-justify"></span>
                    </a>
                </td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input class="form-control" type="text" id="Search_id" name="search[id]" value="<?php echo $search['id'];?>" placeholder="ID"/>
                </td>
                <td class="filter-original">
                    <div class="form-group">
                        <input class="form-control pull-left search" type="text" id="Search_text" name="search[original]" maxlength="255" value="<?php echo htmlspecialchars($search['original'], ENT_QUOTES, 'utf-8');?>" placeholder="Введите фразу для поиска" data-toggle="tooltip"  title="'*' - любое количество символов. '_' - 1 любой символ."/>

                        <select class="form-control pull-right sort" name="search[sort]" data-toggle="tooltip" title="Сортировка">
                            <?php echo SLIAdmin::getHtmlOptions(self::getSortList(), $search['sort']);?>
                        </select>
                        <div class="clearfix"></div>
                    </div>
                    <?php if (SLIAdmin::getAction()=='index') {?>
                        <div class="url_search">
                            <input class="form-control" type="text" id="Search_url" name="search[url]" value="<?php echo htmlspecialchars($search['url'], ENT_QUOTES, 'utf-8');?>" placeholder="Поиск по URL"  data-toggle="tooltip" title="Например /contacts/ - получим фразы используемые на странице контактов."/>
                        </div>
                    <?php }?>
                </td>
                <td class="filter-translate">
                    <div class="form-group">
                        <input class="form-control" type="text" id="Translate_search_text" name="search[translate]" maxlength="255" value="<?php echo htmlspecialchars($search['translate'], ENT_QUOTES, 'utf-8');?>" placeholder="Введите фразу для поиска"  data-toggle="tooltip" title="Поиск по переводу. '*' - любое количество символов. '_' - 1 любой символ."/>
                    </div>

                    <label class="checkbox-inline showEmpty">
                        <input type="checkbox" name="search[show_empty]" value="1" <?php echo $search['show_empty']==1 ? 'checked="checked"' : '';?>/>
                        пустые
                    </label>
                </td>
                <td class="filter-seach-button">
                    <button class="btn btn-primary" data-toggle="tooltip" title="искать"><span class="glyphicon glyphicon-search"></span></button>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
</div>