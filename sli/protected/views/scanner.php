<?php
/**
 * Шаблон работы с сканером
 * @author Ganjar@ukr.net
 */
?>

<form class="form-horizontal" role="form" method="post" action="<?php echo SLIAdmin::getUrl('scanner');?>" id="scanner-form" style="margin-top: 20px;">

<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-primary">
            <div class="panel-heading">Настройки сканера</div>
            <div class="panel-body">

                <div class="form-group row">
                    <label for="scannerType" class="col-md-4 control-label">
                        <?php echo SLIScanner::getAttribute('type')->title;?>
                    </label>
                    <div class="col-md-8">
                        <select class="form-control" name="scanner[type]" title="<?php echo SLIScanner::getAttribute('type')->help;?>" data-toggle="tooltip" id="scannerType">
                            <?php foreach (SLIScanner::getScanTypes() as $key=>$val) {?>
                                <option value="<?php echo $key;?>"<?php echo $key==SLIScanner::getAttribute('type')->value ? ' selected="selected"':'';?>><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="scannerUrl" class="col-md-4 control-label">
                        <?php echo SLIScanner::getAttribute('url')->title;?>
                    </label>
                    <div class="col-md-8">
                        <input id="scannerUrl" class="form-control" type="text" name="scanner[url]" value="<?php echo htmlspecialchars(SLIScanner::getAttribute('url')->value);?>" title="<?php echo SLIScanner::getAttribute('url')->help;?>" data-toggle="tooltip"/>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-offset-4 col-md-8">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="scanner[last]" value="1"<?php echo SLIScanner::getAttribute('last')->value ? ' checked="checked"':'';?>/>
                                <?php echo SLIScanner::getAttribute('last')->title;?>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-offset-4 col-md-8">
                        <button name="yt0" type="submit" class="btn btn-primary"><span class="save">Сканировать</span></button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

</form>
