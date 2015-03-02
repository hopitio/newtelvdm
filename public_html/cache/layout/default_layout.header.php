<!DOCTYPE html>
<?php
/* @var $this View */
?>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $this->title ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="<?php echo site_url('/public/') ?>icon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="<?php echo SITE_URL ?>/public/css/bootstrap.css" media = "screen">
        <link rel="stylesheet" href="<?php echo SITE_URL ?>/public/css/bootswatch.min.css">
        <link rel="stylesheet" href="<?php echo SITE_URL ?>/public/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo SITE_URL ?>/public/plugins/datepicker/css/datepicker3.css">
        <link rel="stylesheet" href="<?php echo SITE_URL ?>/public/plugins/timepicker/css/bootstrap-timepicker.min.css">
        <link rel="stylesheet" href="<?php echo SITE_URL ?>/public/css/custom.css">
        <!--HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src = "../bower_components/html5shiv/dist/html5shiv.js"></script>
        <script src="../bower_components/respond/dest/respond.min.js"></script>
        <![endif]-->
        <script src="<?php echo SITE_URL ?>/public/js/jquery-1.10.2.min.js"></script>
    </head>
    <body>
        <div id="top_nav">
            <div class="container">
                <div class="logo">
                    <div class="pull-right">
                        <?php if (user()->is_logged): ?>
                            <?php echo user()->name ?>
                            &nbsp;|&nbsp;
                            <a href="<?php echo site_url('/change_password') ?>">Đổi mật khẩu</a>
                            &nbsp;|&nbsp;
                            <a href="<?php echo site_url('/login') ?>"> Đăng xuất</a>
                        <?php endif; ?>
                    </div>
                </div>
                <ul class="nav_items">
                    <?php foreach ($this->main_nav as $k => $nav): ?>
                        <?php
                        $active = $k == $this->active_main_nav ? 'active' : '';
                        ?>
                        <li class="<?php echo $active ?>">
                            <a href="<?php echo $nav['url'] ?>"><?php echo $nav['label'] ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div><!--top_nav-->
        <div class="container" >
            <div id='main-content' id="main-content">
                <?php if ($this->heading): ?>
                    <h2><center><?php echo $this->heading ?></center></h2>
                    <hr>
                    <br><br>
                <?php endif; ?>
                