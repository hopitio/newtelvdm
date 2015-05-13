<?php

$app_id = get_request_var('app_id');
$db = DB::get_instance();

$view_data['app'] = $db->GetRow("SELECT * FROM appointments WHERE app_id=?", array($app_id));
$attendiees = $db->GetCol("SELECT fk_user FROM nt_attendiees WHERE fk_appointment=?", array($app_id));

if (!user()->is_admin && !in_array(user()->pk_user, $attendiees))
{
    forbidden();
}

View::get_instance()
        ->set_layout(null)
        ->set_data($view_data)
        ->render('join_conf');
