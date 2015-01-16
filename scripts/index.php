<?php

require_login();
$db = DB::get_instance();

if (get_post_var('btn_request_conf'))
{
    $password = uniqid();
    $start_date = DateTimeEx::createFrom_dmY_Hi(get_post_var('txt_conf_start_date') . ' ' . get_post_var('txt_conf_start_time'));
    $end_date = DateTimeEx::createFrom_dmY_Hi(get_post_var('txt_conf_end_date') . ' ' . get_post_var('txt_conf_end_time'));
    $arr_conf_data = array(
        'confroom_id' => 'conf_' . uniqid() . '(conf.vm)',
        'password'    => uniqid(),
        'topic'       => get_post_var('txt_conf_name'),
        'uid'         => 1,
        'startTime'   => $start_date->toIsoString(),
        'finishTime'  => $end_date->toIsoString(),
        'tzone'       => 0,
        'lid'         => 0,
        'wait_owner'  => 0,
        'sequence'    => 5,
        'preTime'     => 900,
        'attendees'   => '[]',
        'owner_id'    => user()->pk_user
    );

    $app_id = $db->insert('appointments', $arr_conf_data);

    $arr_opt_data = array(
        'app_id'             => $app_id,
        'party_limit'        => 100,
        'sharing_on'         => 1,
        'agent_on'           => 1,
        'hd_on'              => 1,
        'conf_dump_on'       => 0,
        'sip'                => '',
        'sip_proxy'          => '',
        'sip_onlyaudio'      => 0,
        'show_only_owner'    => 0,
        'translation'        => 1,
        'translation_pass'   => $password,
        'translation_pcount' => 1,
        'bitrate_limit'      => 12000
    );
    $db->insert('appointments_options', $arr_opt_data);
    $db->insert('nt_attendiees', array('fk_user' => user()->pk_user, 'fk_appointment' => $app_id));

    redirect('/request_result');
}

$today = DateTimeEx::create($db->get_date())->format('Y-m-d');

$sql = "SELECT app.*,crc.status FROM appointments app"
        . " LEFT JOIN confroomscontrol crc ON app.app_id=crc.id"
        . " WHERE is_deleted=0"
        . " AND owner_id=?"
        . " AND finishTime >= '$today'"
        . " AND is_approved=1"
        . " ORDER BY startTime";
$view_data['arr_host_conf'] = $db->GetAll($sql, array(user()->pk_user));

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
