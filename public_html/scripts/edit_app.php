<?php

require_login();

$app_id = get_request_var('app_id');
$db = DB::get_instance();

if ($_POST)
{
    $app_id = get_post_var('hdn_app_id');
    $start_date = DateTimeEx::createFrom_dmY_Hi(get_post_var('txt_conf_start_date') . ' ' . get_post_var('txt_conf_start_time'))->addHour(-8);
    $end_date = DateTimeEx::createFrom_dmY_Hi(get_post_var('txt_conf_end_date') . ' ' . get_post_var('txt_conf_end_time'))->addHour(-7);
    $topic = get_post_var('txt_conf_name');
    $show_only_owner = (int) get_post_var('chk_show_only_owner');

    if ($start_date >= $end_date)
    {
        $view_data['error'] = "Ngày bắt đầu phải trước ngày kết thúc";
    }

    if (!isset($view_data['error']))
    {

        $update_data = array(
            'startTime'   => $start_date->toIsoString(),
            'finishTime'  => $end_date->toIsoString(),
            'topic'       => $topic,
            'owner_id'    => get_post_var('sel_conf_owner'),
            'is_approved' => 0
        );

        if ($app_id)
        {
            //update
            $db->update('appointments', $update_data, 'app_id=?', array($app_id));
            $db->update('appointments_options', array('show_only_owner' => $show_only_owner), 'app_id=?', array($app_id));
        }
        else
        {
            //insert
            $password = uniqid();
            $update_data += array(
                'confroom_id' => 'conf_' . uniqid() . '(conf.vm)',
                'password'    => $password,
                'uid'         => 1,
                'tzone'       => 7,
                'lid'         => 0,
                'wait_owner'  => 0,
                'sequence'    => 5,
                'preTime'     => 900,
                'is_approved' => 1,
                'attendees'   => '[]'
            );
            $app_id = $db->insert('appointments', $update_data);

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
                'show_only_owner'    => $show_only_owner,
                'translation'        => 1,
                'translation_pass'   => $password,
                'translation_pcount' => 1,
                'bitrate_limit'      => 12000
            );
            $db->insert('appointments_options', $arr_opt_data);
        }

        //attendiees
        $owner_id = user()->pk_user;
        $arr_attendiees = get_post_var('chk_user', array());
        if (!in_array($owner_id, $arr_attendiees))
        {
            $arr_attendiees[] = $owner_id;
        }

        $db->delete('nt_attendiees', "fk_appointment=?", array($app_id));
        foreach ($arr_attendiees as $user_id)
        {
            $db->insert('nt_attendiees', array(
                'fk_appointment' => $app_id,
                'fk_user'        => $user_id
            ));
        }

        //update appointments uid
        //tra cuu videomost user
        $account = $db->GetOne("SELECT c_account FROM nt_user WHERE pk_user=?", array($owner_id));
        $uid = $db->GetOne("SELECT uid FROM users WHERE login=?", array($account));
        $db->update('appointments', array('uid' => $uid), 'app_id=?', array($app_id));

        //gui sms cho admin
        $msg = "He thong HNTT TP\n" .
                tieng_viet_khong_dau(user()->name) . ' chinh sua cuoc hop: ' . tieng_viet_khong_dau(get_post_var('txt_conf_name'));
        send_sms(SMS_ADMIN, $msg);

        redirect('/');
    }
}

$view_data['arr_user'] = $db->GetAll("SELECT * FROM nt_user WHERE c_deleted=0 ORDER BY c_sort");
$view_data['app'] = $db->GetRow("SELECT * FROM appointments WHERE app_id=?", array($app_id));
$view_data['arr_attendiees'] = $db->GetCol("SELECT fk_user FROM nt_attendiees WHERE fk_appointment=?", array($app_id));
$view_data['show_only_owner'] = (int) $db->GetOne("SELECT show_only_owner FROM appointments_options WHERE app_id=?", array($app_id));

View::get_instance()
        ->set_title($view_data['app']['topic'])
        ->set_heading('Sửa lịch họp')
        ->set_data($view_data)
        ->render('edit_app');

