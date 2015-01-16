<?php

class Bootstrap
{

    public $default_uri = 'index';
    public $default_admin_uri = 'admin/approve';

    function __construct($uri)
    {
        $uri = trim($uri, "\\\/ ");
        $start_uri = strpos($uri, 'index.php?');

        if ($start_uri !== false)
        {
            $uri = (string) substr($uri, $start_uri + strlen('index.php') + 1);
        }

        //xu ly bien get
        if (strpos($uri, '?'))
        {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        if (strpos($uri, '&'))
        {
            $uri = substr($uri, 0, strpos($uri, '&'));
        }
        if (HOST_SUFFIX && strpos($uri, HOST_SUFFIX) === 0)
        {
            $uri = substr($uri, strpos($uri, HOST_SUFFIX . '/') + strlen(HOST_SUFFIX . '/'));
        }
        if (!$uri)
        {
            $uri = $this->default_uri;
        }

        if ($uri == 'admin')
        {
            $uri = $this->default_admin_uri;
        }

        global $URI;
        $URI = $uri;

        //init database connection
        $config = new DB_Config;
        $config->debug = DEBUG_MODE ? true : false;
        $config->type = DB_TYPE;
        $config->host = DB_HOST;
        $config->database_name = DB_NAME;
        $config->user = DB_USER;
        $config->password = DB_PASS;
        $config->cache_dir = BASE_DIR . 'cache/adodb';
        $config->cache_sec = 3600 * 24;

        DB::config($config);

        $script = BASE_DIR . 'scripts/' . trim($uri, "\/\\") . '.php';
        if (file_exists($script))
        {
            require $script;
        }
        else
        {
            header("HTTP/1.0 404 Not Found");
            echo '<h1>404 Không tìm thấy đường dẫn</h1>';
            echo 'Địa chỉ bạn tìm không khả dụng';
        }
    }

}
