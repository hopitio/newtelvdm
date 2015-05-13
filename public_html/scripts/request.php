<?php

require_login();
$db = DB::get_instance();

if (get_post_var('btn_request_conf'))
{
    $password = uniqid();
    $start_date = DateTimeEx::createFrom_dmY_Hi(get_post_var('txt_conf_start_date') . ' ' . get_post_var('txt_conf_start_time'))->addHour(-8);
    $end_date = DateTimeEx::createFrom_dmY_Hi(get_post_var('txt_conf_end_date') . ' ' . get_post_var('txt_conf_end_time'))->addHour(-7);
    $now = DateTimeEx::create()->addHour(-7);

    if ($start_date >= $end_date)
    {
        $view_data['request_error'] = "Ngày bắt đầu phải trước ngày kết thúc";
    }
    else if ($start_date->addHour(1) <= $now)
    {
        $view_data['request_error'] = "Thời gian bắt đầu cần phải ở tương lai";
    }

    if (!isset($view_data['request_error']))
    {
        //videomost user id
        $uid = $db->GetOne("SELECT uid FROM users WHERE login=?", array(user()->account));
        $arr_conf_data = array(
            'confroom_id' => 'conf_' . uniqid() . '(conf.vm)',
            'password'    => $password,
            'topic'       => get_post_var('txt_conf_name'),
            'uid'         => $uid,
            'startTime'   => $start_date->toIsoString(),
            'finishTime'  => $end_date->toIsoString(),
            'tzone'       => 7,
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
            'conf_dump_on'       => 1,
            'sip'                => '',
            'sip_proxy'          => '',
            'sip_onlyaudio'      => 0,
            'show_only_owner'    => (int) get_post_var('chk_show_only_owner'),
            'translation'        => 1,
            'translation_pass'   => $password,
            'translation_pcount' => 1,
            'bitrate_limit'      => 12000
        );
        $db->insert('appointments_options', $arr_opt_data);
        $db->insert('nt_attendiees', array('fk_user' => user()->pk_user, 'fk_appointment' => $app_id));

        redirect('/request_result');
    }
}

$sql = "
    SELECT * FROM appointments app
    WHERE app.owner_id=?
    AND app.is_deleted=0
    AND app.is_approved=0
    ORDER BY app.startTime
";
$view_data['arr_conf'] = $db->GetAll($sql, array(user()->pk_user));

View::get_instance()
        ->set_active_main_nav('request')
        ->set_title('Yêu cầu cuộc họp')
        ->set_heading('Yêu cầu cuộc họp')
        ->set_data($view_data)
        ->render('request');

