<?php

require_login();
$app_id = get_request_var('app_id');
$db = DB::get_instance();

$app = $db->GetRow("SELECT * FROM appointments app WHERE app_id=?", array($app_id));
$attendiees = $db->GetCol("SELECT fk_user FROM nt_attendiees WHERE fk_appointment=?", array($app_id));

if (!$app)
{
    forbidden();
}

if (!user()->is_admin && !in_array(user()->pk_user, $attendiees))
{
    forbidden();
}

$view_data['app'] = $app;

View::get_instance()
        ->set_title($app['topic'])
        ->set_heading($app['topic'])
        ->set_data($view_data)
        ->render('recording');
