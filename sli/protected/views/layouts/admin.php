<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" />
    <title><?php echo $title;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="static/img/favicon.png" />

    <?php if (SLI_ADMIN_DEBUG) {?>
    <script type="text/javascript">
        less = {};
        less.async = false;
        less.env = 'development';
    </script>

    <link href="static/css/router.less" type="text/css" rel="stylesheet/less">
    <script type="text/javascript">
        localStorage.clear();
    </script>
    <script src="static/js/less-1.4.1.min.js"></script>
    <?php } else {?>
    <link rel="stylesheet" href="static/css/router.css"/>
    <?php }?>
    <script type="text/javascript">
        var SLItranslateApiKey = '<?php echo SLISettings::getInstance()->getVar('translateKey');?>';
    </script>
    <script src="static/js/jquery.min.js"></script>
    <script src="static/bootstrap/js/bootstrap.min.js"></script>
    <script src="static/js/jquery.cookie.js"></script>
    <script src="static/js/jquery.autoresize.js"></script>
    <script src="static/js/common.js"></script>
    <script src="static/js/site/translate.js"></script>
</head>

<body>

    <header role="navigation" class="navbar navbar-inverse nav-header" id="header">
        <div class="container">
            <div class="navbar-header">
                <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo SLIAdmin::getUrl('index');?>">
                    <img src="static/img/logo.png" alt=""/>
                </a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <?php
                    $tabs = SLIAdmin::getTabs();
                    if (!empty($tabs)) { foreach($tabs as $alias => $tab) {?>
                        <li><a href="<?php echo SLIAdmin::getUrl($tab['action']);?>" title="<?php echo $tab['title'];?>" class="<?php echo $alias;?>">
                                <span></span>
                                <?php echo $tab['title'];?>
                            </a></li>
                    <?php }}?>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="#sli-info" data-toggle="modal">
                            <span class="glyphicon glyphicon-info-sign"></span>
                            О программе
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo SLIAdmin::getUrl('logout');?>" class="exit" onclick="return confirm('Выйти?');">
                            <span class="glyphicon glyphicon-log-out"></span>
                            Выйти
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <div class="container" id="content">
    	<?php echo $content;?>
    </div>
    <div id="loading"></div>

    <?php SLIAdmin::renderPartial('parts/_sli_info_modal');?>
</body>
</html>