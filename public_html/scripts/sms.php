<?php

require_login();

$arr_id       = get_request_var('user');
$arr_selected = get_request_var('selected_user');
$app_id       = get_request_var('app_id');

$app = DB::get_instance()->GetRow("SELECT app.*, u.c_name AS owner_name"
        . " FROM appointments app"
        . " INNER JOIN nt_user u ON app.owner_id=u.pk_user"
        . " WHERE app_id=?", array($app_id));

$data['app']          = $app;
$data['arr_user']     = DB::get_instance()->GetAll("SELECT * FROM nt_user WHERE pk_user IN($arr_id) ORDER BY c_name");
$data['arr_selected'] = explode(',', $arr_selected);

if ($app)
{
    $title = 'SMS phòng: ' . $app['topic'];
}
else
{
    $title = "Gửi tin nhắn SMS";
}

View::get_instance()
        ->set_heading($title)
        ->set_data($data)
        ->set_title($title)
        ->render('sms');

