<?php

define('DEBUG_MODE', 1);
define('HOST_SUFFIX', 'newtel');

define('DB_TYPE', 'mysqli');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'videomost');

define('SITE_URL', '/' . HOST_SUFFIX . '/');

define('VIDEOMOST_URL', '/service/');
define('VIDEOMOST_DIR', '/usr/share/videomost/service/');
define('SMS_URL', 'http://172.16.10.117:13013/cgi-bin/sendsms');
define('SMS_USER', 'newtel');
define('SMS_PASS', 'newtel123');
define('SMS_ADMIN', '01666244670');
define('SMS_HEADER', 'He thong HNTT TP');

//tài khoản videomost
define('VIDEOMOST_ADMIN_ACC', 'admin');
define('VIDEOMOST_ADMIN_PASS', 'newtel123');

//có trick HD không
define('VIDEOMOST_TRICK_HD', '1');


date_default_timezone_set('asia/saigon');

