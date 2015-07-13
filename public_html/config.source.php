<?php

define('DEBUG_MODE', 0);
define('HOST_SUFFIX', 'newtel');

define('DB_TYPE', 'mysqli');
define('DB_HOST', 'localhost');
define('DB_USER', '{{DB_USER}}');
define('DB_PASS', '{{DB_PASS}}');
define('DB_NAME', '{{DB_NAME}}');

define('SITE_URL', '/' . HOST_SUFFIX . '/');

define('VIDEOMOST_URL', '/service/');
define('VIDEOMOST_DIR', '{{VIDEOMOST_DIR}}');

define('SMS_URL', '{{SMS_URL}}');
define('SMS_USER', '{{SMS_USER}}');
define('SMS_PASS', '{{SMS_PASS}}');
define('SMS_ADMIN', '01666244670');
define('SMS_HEADER', 'He thong HNTT TP');

//tài khoản videomost
define('VIDEOMOST_ADMIN_ACC', '{{VIDEOMOST_ADMIN_ACC}}');
define('VIDEOMOST_ADMIN_PASS', '{{VIDEOMOST_ADMIN_PASS}}');

//có trick HD không
define('VIDEOMOST_TRICK_HD', '{{VIDEOMOST_TRICK_HD}}');


date_default_timezone_set('asia/saigon');

