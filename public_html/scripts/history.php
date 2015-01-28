<?php

require_login();
$db = DB::get_instance();

$limit = 20;
$offset = ((int) get_request_var('page', 1) - 1) * $limit;

$today = DateTimeEx::create($db->get_date())->format('Y-m-d');

$sql = "SELECT app.*, u.c_name AS owner_name FROM appointments app
            INNER JOIN nt_attendiees att ON app.app_id=att.fk_appointment AND att.fk_user=?
            INNER JOIN nt_user u ON app.owner_id=u.pk_user"
        . " WHERE (is_deleted=0 OR (is_deleted=1 AND app.owner_id=?))"
        . " AND finishTime < '$today'"
        . " AND is_approved=1"
        . " ORDER BY startTime DESC
            LIMIT $limit OFFSET $offset";

$sql_count = "SELECT COUNT(*) FROM appointments app
            INNER JOIN nt_attendiees att ON app.app_id=att.fk_appointment AND att.fk_user=?"
        . " WHERE (is_deleted=0 OR (is_deleted=1 AND app.owner_id=?))"
        . " AND finishTime < '$today'"
        . " AND is_approved=1";

$view_data['arr_conf'] = $db->GetAll($sql, array(user()->pk_user, user()->pk_user));
$total_record = (int) $db->GetOne($sql_count, array(user()->pk_user, user()->pk_user));
$view_data['total_page'] = max(array(1, ceil($total_record / $limit)));
$view_data['page'] = (int) get_request_var('page', 1);

View::get_instance()
        ->set_title('Tra cứu cuộc họp cũ')
        ->set_heading('Tra cứu cuộc họp cũ')
        ->set_active_main_nav('history')
        ->set_data($view_data)
        ->render('history');
