<?php

$app_id = get_request_var('app_id');
$db = DB::get_instance();
$appointment = $db->GetRow("SELECT * FROM appointments WHERE app_id=?", array($app_id));

if (get_post_var('btn_add'))
{
    $arr_add = get_post_var('chk_left');
    foreach ($arr_add as $v)
    {
        $db->insert('nt_attendiees', array('fk_appointment' => $app_id, 'fk_user' => $v));
    }
    redirect('/attendiees', array('app_id' => $app_id));
}
if (get_post_var('btn_remove'))
{
    $arr_remove = get_post_var('chk_right');
    foreach ($arr_remove as $v)
    {
        $db->delete('nt_attendiees', 'fk_appointment=? AND fk_user=?', array($app_id, $v));
    }
    redirect('/attendiees', array('app_id' => $app_id));
}

$view_data['arr_user'] = $db->GetAll("SELECT * FROM nt_user WHERE c_deleted=0 ORDER BY c_sort");
$view_data['arr_attendiees'] = $db->GetCol("SELECT fk_user FROM nt_attendiees WHERE fk_appointment=?", array($app_id));
$view_data['appointment'] = $appointment;

View::get_instance()
        ->set_title($appointment['topic'])
        ->set_heading($appointment['topic'])
        ->set_data($view_data)
        ->render('attendiees');
