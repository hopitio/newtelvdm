<?php

$app_id = get_request_var('app_id');
$db = DB::get_instance();

if (get_post_var('btn_submit'))
{
    $app_id = get_post_var('hdn_app_id');
    $start_date = DateTimeEx::createFrom_dmY_Hi(get_post_var('txt_conf_start_date') . ' ' . get_post_var('txt_conf_start_time'));
    $end_date = DateTimeEx::createFrom_dmY_Hi(get_post_var('txt_conf_end_date') . ' ' . get_post_var('txt_conf_end_time'));
    $topic = get_post_var('txt_conf_name');

    $update_data = array(
        'startTime'  => $start_date->toIsoString(),
        'finishTime' => $end_date->toIsoString(),
        'topic'      => $topic
    );
    $db->update('appointments', $update_data, 'app_id=?', array($app_id));
    redirect('/admin/approve');
}

$view_data['app'] = $db->GetRow("SELECT * FROM appointments WHERE app_id=?", array($app_id));

View::get_instance()
        ->set_title($view_data['app']['topic'])
        ->set_heading('Sửa hội nghị')
        ->set_data($view_data)
        ->render('admin/edit_appointment');
