<?php
/**
 * Подключение ядра системы
 * @author Ganjar@ukr.net
 */

$fileList = glob(SLI_WORK_DIR.'/core/SLI*.php');
foreach($fileList as $file) {
    require_once($file);
}
