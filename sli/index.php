<?php
/**
 * Site Language Injection
 * Админка SLI
 * @author GANJAR
 * @link http://sli.su/
 */
define('SLI_ADMIN_DEBUG', false);
define('SLI_WORK_DIR', dirname(__FILE__).'/protected');
require_once SLI_WORK_DIR.'/core/includeAll.php';

SLIAdmin::init();