<?php

require_login(true);
if (!user()->is_admin)
{
    forbidden();
}

$action = get_post_var('action');

switch ($action) {
    case 'delete_conference':
        delete_conf();
        break;
    default:
        header('HTTP/1.1 400 BAD REQUEST');
        echo 'Không có action này';
        die;
}

function delete_conf()
{
    $db = DB::get_instance();
    $app_id = get_post_var('app_id');
    $db->update('appointments', array('is_deleted' => 1), 'app_id=?', array($app_id));
}
