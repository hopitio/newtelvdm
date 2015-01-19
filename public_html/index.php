<?php

define('BASE_DIR', __DIR__ . '/');

//install nếu chưa khởi tạo
if (!file_exists(BASE_DIR . 'config.php'))
{
    require(BASE_DIR . 'install/install.php');
    die;
}

require BASE_DIR . 'config.php';

if (DEBUG_MODE)
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}
else
{
    ini_set('display_errors', 0);
    error_reporting(0);
}
require BASE_DIR . 'libs/functions.php';
require BASE_DIR . 'libs/adodb5/adodb.inc.php';

spl_autoload_register(function($class)
{
    $filename = BASE_DIR . 'libs/' . $class . '.php';
    if (file_exists($filename))
    {
        require_once $filename;
    }
});

$app = new Bootstrap($_SERVER['REQUEST_URI']);

