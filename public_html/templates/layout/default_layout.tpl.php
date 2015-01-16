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
        <div class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a href="<?php echo SITE_URL ?>" class="navbar-brand">
                        <img src='<?php echo SITE_URL ?>public/images/logo.png' style='max-height: 100%;max-width: 100%;'/>
                    </a>
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="navbar-collapse collapse" id="navbar-main">
                    <ul class="nav navbar-nav">
                        <?php foreach ($this->main_nav as $k => $nav): ?>
                            <?php
                            $active = $k == $this->active_main_nav ? 'active' : '';
                            ?>
                            <li class="<?php echo $active ?>">
                                <a href="<?php echo $nav['url'] ?>"><?php echo $nav['label'] ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <?php if (user()->is_logged): ?>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="dropdown-user"><i class="fa fa-user"></i> <?php echo user()->name ?></a>
                                <ul class="dropdown-menu" aria-labelledby="dropdown-user">
                                    <li><a href="<?php echo site_url('/change_password') ?>">Đổi mật khẩu</a></li>
                                    <li><a href="<?php echo site_url('/login') ?>">Đăng xuất</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if (user()->is_admin): ?>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="dropdown-admin"><i class="fa fa-cog"></i></a>
                                <ul class="dropdown-menu" aria-labelledby="dropdown-user">
                                    <li><a href="<?php echo site_url('/admin/account') ?>">Quản trị tài khoản</a></li>
                                    <li><a href="<?php echo site_url('/admin/approve') ?>">Duyệt lịch họp</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>

                </div>
            </div>
        </div>
        <div class="container" >
            <div id='main-content'>
                <?php if ($this->heading): ?>
                    <h2><center><?php echo $this->heading ?></center></h2>
                    <hr>
                    <br><br>
                <?php endif; ?>
                {{LAYOUT_CONTENT}}
            </div>
            <footer>
                <div class="row">
                    <div class="col-lg-12">
                        <hr>
                        Giải pháp hội nghị truyền hình - Phát triển bởi công ty cổ phần viễn thông New-Telecom.<br>
                        Website: <a href='http://newtel.vn'>http://newtel.vn</a>
                    </div>
                </div>
            </footer>
        </div>


        <script src="<?php echo SITE_URL ?>/public/js/bootstrap.min.js"></script>
        <script src="<?php echo SITE_URL ?>/public/js/bootswatch.js"></script>
        <script src="<?php echo SITE_URL ?>/public/plugins/validation/jquery.validate.min.js"></script>
        <script src="<?php echo SITE_URL ?>/public/plugins/validation/additional-methods.min.js"></script>
        <script src="<?php echo SITE_URL ?>/public/plugins/datepicker/js/bootstrap-datepicker.js"></script>
        <script src="<?php echo SITE_URL ?>/public/plugins/datepicker/js/locales/bootstrap-datepicker.vi.js"></script>
        <script src="<?php echo SITE_URL ?>/public/plugins/timepicker/js/bootstrap-timepicker.min.js"></script>
        <script src="<?php echo SITE_URL ?>/public/js/custom.js"></script>
    </body>
</html>
