<?php

define('DEBUG_MODE', 0);
define('HOST_SUFFIX', 'newtel');

define('DB_TYPE', 'mysqli');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'newtel123');
define('DB_NAME', 'videomost');

define('SITE_URL', '/' . HOST_SUFFIX . '/');

define('VIDEOMOST_URL', '/service/');
define('VIDEOMOST_DIR', '/usr/share/videomost/service/');

define('SMS_URL', 'http://10.202.0.98:13013/cgi-bin/sendsms');
define('SMS_USER', 'newtel');
define('SMS_PASS', 'newtel123');
define('SMS_ADMIN', '0937373753');
define('SMS_HEADER', 'HE THONG HNTH TP');

//tài khoản videomost
define('VIDEOMOST_ADMIN_ACC', 'admin');
define('VIDEOMOST_ADMIN_PASS', 'hntt123456');

//có trick HD không
define('VIDEOMOST_TRICK_HD', '');

define('MAX_USER', 3);


date_default_timezone_set('asia/hochiminh');

