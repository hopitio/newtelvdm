<?php

require_login();
$db = DB::get_instance();

if (get_post_var('btn_request_conf'))
{
    $password = uniqid();
    $start_date = DateTimeEx::createFrom_dmY_Hi(get_post_var('txt_conf_start_date') . ' ' . get_post_var('txt_conf_start_time'))->addHour(-7);
    $end_date = DateTimeEx::createFrom_dmY_Hi(get_post_var('txt_conf_end_date') . ' ' . get_post_var('txt_conf_end_time'))->addHour(-7);

    if ($start_date >= $end_date)
    {
        $view_data['request_error'] = "Ngày bắt đầu phải trước ngày kết thúc";
    }

    if (!isset($view_data['request_error']))
    {
        $arr_conf_data = array(
            'confroom_id' => 'conf_' . uniqid() . '(conf.vm)',
            'password'    => $password,
            'topic'       => get_post_var('txt_conf_name'),
            'uid'         => 1,
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
}

View::get_instance()
        ->set_active_main_nav('request')
        ->set_title('Yêu cầu cuộc họp')
        ->set_heading('Yêu cầu cuộc họp')
        ->render('request');

