<?php

require_login();
if (!user()->is_admin)
{
    forbidden();
}

$db = DB::get_instance();
$page = (int) get_request_var('page', 1);

if ($app_id = get_post_var('btn_approve'))
{
    $db->update('appointments', array('is_approved' => 1), 'app_id=?', array($app_id));
    redirect('/admin/approve', array('page' => $page));
}
else if ($app_id = get_post_var('btn_decline'))
{
    $db->update('appointments', array('is_approved' => 0), 'app_id=?', array($app_id));
    redirect('/admin/approve', array('page' => $page));
}

$cond = "is_deleted=0";
if ($search = get_request_var('search'))
{
    $cond .= " AND (app.topic LIKE '%{$search}%' OR u.c_name LIKE '%{$search}%')";
}

$limit = 20;
$offset = ($page - 1) * $limit;
$sql = "SELECT app.*,crc.status, u.c_name AS owner_name"
        . " FROM appointments app"
        . " INNER JOIN nt_user u ON app.owner_id=u.pk_user"
        . " LEFT JOIN confroomscontrol crc ON app.app_id=crc.id"
        . " WHERE $cond"
        . " ORDER BY startTime DESC"
        . " LIMIT $limit OFFSET $offset";

$sql_count = "SELECT COUNT(*)"
        . " FROM appointments app"
        . " INNER JOIN nt_user u ON app.owner_id=u.pk_user"
        . " WHERE $cond";

$view_data['arr_conf'] = $db->GetAll($sql);
$total_record = $db->GetOne($sql_count);

$view_data['total_page'] = max(array(1, ceil($total_record / $limit)));

View::get_instance()
        ->set_title('Duyệt lịch họp')
        ->set_active_main_nav('approve')
        ->set_heading('Duyệt lịch họp')
        ->set_data($view_data)
        ->render('admin/approve');

