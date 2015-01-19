<?php

if (!defined('BASE_DIR'))
{
    die('no direct script access');
}

require_once __DIR__ . '/install_lib.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

function fetch_array($arr, $key, $default = null)
{
    return isset($arr[$key]) ? $arr[$key] : $default;
}

$db_user = fetch_array($_POST, 'txt_db_user', 'root');
$db_pass = fetch_array($_POST, 'txt_db_pass', 'newtel123');
$db_name = fetch_array($_POST, 'txt_db_name', 'videomost');

$vdm_root = fetch_array($_POST, 'txt_vdm_root', '/usr/share/videomost/service/');
$vdm_admin_acc = fetch_array($_POST, 'txt_vdm_admin_acc', 'admin');
$vdm_admin_pass = fetch_array($_POST, 'txt_vdm_admin_pass', 'newtel123');

$sms_url = fetch_array($_POST, 'txt_sms_url', 'javascript:;');

if (!empty($_POST))
{
    if (!file_exists($vdm_root . '/index.php'))
    {
        die('Thư mục videmost không đúng');
    }

    install_lib::connect_db('mysqli', $db_user, $db_pass, $db_name);
    install_lib::setup_db();

    //copy files
    $dom_files = simplexml_load_file(__DIR__ . '/file_map.xml', 'SimpleXMLElement', LIBXML_NOCDATA);
    $r = $dom_files->xpath('//file');
    if (!empty($r))
    {
        foreach ($r as $dom_file)
        {
            $source = __DIR__ . '/files/' . $dom_file->name;
            $dest = $vdm_root . '/' . $dom_file->dest;
            $content = file_get_contents($source);
            if (!file_put_contents($dest, $content))
            {
                die('Không copy được file');
            }
        }
    }

    //put config
    $config = file_get_contents(BASE_DIR . 'config.source.php');
    $arr_search = array(
        "{{DB_USER}}",
        "{{DB_PASS}}",
        "{{DB_NAME}}",
        "{{VIDEOMOST_DIR}}",
        "{{VIDEOMOST_ADMIN_ACC}}",
        "{{VIDEOMOST_ADMIN_PASS}}",
        "{{SMS_URL}}"
    );
    $arr_replace = array(
        $db_user,
        $db_pass,
        $db_name,
        $vdm_root,
        $vdm_admin_acc,
        $vdm_admin_pass,
        $sms_url
    );
    $config = str_replace($arr_search, $arr_replace, $config);
    if (!file_put_contents(BASE_DIR . '/config.php', $config))
    {
        die('Không khởi tạo được file config');
    }

    die('<h1>Cài đặt thành công</h1>');
}
?>


<?php

require BASE_DIR . 'templates/install.tpl.php';


