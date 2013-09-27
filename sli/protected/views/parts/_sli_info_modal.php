<?php
/**
 * Шаблон вывода иформации о SLI
 * @author Ganjar@ukr.net
 */
?>

<div class="modal fade" id="sli-info" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">О программе</h4>
            </div>
            <div class="modal-body">
                <dl class="dl-horizontal">
                    <dt>
                        Текущая версия:
                    </dt>
                    <dd>
                        <b><?php echo SLIVersion::CURRENT_VERSION;?></b> (<a href="<?php echo SLIVersion::getDownloadLink();?>" target="_blank">Проверить обновления</a>)
                    </dd>

                    <dt>
                        Лицензия:
                    </dt>
                    <dd>
                        <a href="http://ru.wikipedia.org/wiki/Donationware" target="_blank">Donationware</a>
                    </dd>

                    <dt>
                        Автор:
                    </dt>
                    <dd>
                         Богдан Рыхаль (GANJAR)
                    </dd>

                    <dt>
                        Сайт:
                    </dt>
                    <dd>
                        <a href="http://<?php echo SLIVersion::SITE_URL;?>/" target="_blank"><?php echo SLIVersion::SITE_URL;?></a>
                    </dd>

                    <dt>
                        Документация:
                    </dt>
                    <dd>
                        <a href="<?php echo SLIVersion::getDocumentationLink();?>" target="_blank"><?php echo SLIVersion::getDocumentationLink();?></a>
                    </dd>
                </dl>

                <blockquote class="additional-info">
                    <p>
                        Ваши предложения, замечания, а так же вопросы связанные с доработкой программного продукта
                        пишите на email: <a href="mailto:<?php echo SLIVersion::getContactEmail();?>"><?php echo SLIVersion::getContactEmail();?></a><br>
                        Либо воспользуйтесь <a href="<?php echo SLIVersion::getContactLink();?>">формой обратной связи</a> на нашем сайте.
                    </p>
                </blockquote>

                <a href="<?php echo SLIVersion::getDonateLink();?>" class="btn btn-success donate-link" target="_blank">Поддержать выход новых версий!</a>
            </div>
        </div>
    </div>
</div>