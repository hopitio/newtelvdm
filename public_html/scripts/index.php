<?php

require_login();
$db = DB::get_instance();



$today = DateTimeEx::create($db->get_date())->format('Y-m-d');

$sql = "SELECT app.*,crc.status FROM appointments app"
        . " LEFT JOIN confroomscontrol crc ON app.app_id=crc.id"
        . " WHERE is_deleted=0"
        . " AND owner_id=?"
        . " AND finishTime >= '$today'"
        . " AND is_approved=1"
        . " ORDER BY startTime";
$view_data['arr_host_conf'] = $db->GetAll($sql, array(user()->pk_user));
foreach ($view_data['arr_host_conf'] as &$conf)
{
    //thêm url sms
    $arr_attendiees = $db->GetCol("SELECT fk_user FROM nt_attendiees WHERE fk_appointment=?", array($conf['app_id']));
    $conf['sms_url'] = sms_url($arr_attendiees, $arr_attendiees, $conf['app_id']);
}

$sql = "SELECT app.*,crc.status, u.c_name AS owner_name"
        . " FROM appointments app"
        . " INNER JOIN nt_attendiees att ON att.fk_appointment=app.app_id AND att.fk_user=?"
        . " INNER JOIN nt_user u ON app.owner_id=u.pk_user"
        . " LEFT JOIN confroomscontrol crc ON app.app_id=crc.id"
        . " WHERE is_deleted=0"
        . " AND owner_id<>?"
        . " AND finishTime >= '$today'"
        . " AND is_approved=1"
        . " ORDER BY startTime";
$view_data['arr_invited_conf'] = $db->GetAll($sql, array(user()->pk_user, user()->pk_user));

View::get_instance()
        ->set_active_main_nav('schedule')
        ->set_heading('Danh sách hội nghị')
        ->set_data($view_data)
        ->render('index');
