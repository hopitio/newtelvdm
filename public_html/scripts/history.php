<?php

require_login();
$db = DB::get_instance();

$limit = 20;
$offset = ((int) get_request_var('page', 1) - 1) * $limit;

$today = DateTimeEx::create($db->get_date())->format('Y-m-d');

$params = array(user()->pk_user);
$cond_is_admin = user()->is_admin ? 'is_deleted=1' : '1=0';

$join = '';
if (!user()->is_admin)
{
    $join = "INNER JOIN nt_attendiees att ON app.app_id=att.fk_appointment AND att.fk_user=?";
    $params[] = user()->pk_user;
}
$pk_user = user()->pk_user;
$sql = "SELECT DISTINCT app.*, u.c_name AS owner_name FROM appointments app
            $join
            INNER JOIN nt_user u ON app.owner_id=u.pk_user"
        . " WHERE (is_deleted=0"
        . " AND finishTime < '$today'"
        . " AND is_approved=1)"
        . " OR $cond_is_admin
            OR owner_id=$pk_user
                    "
        . " ORDER BY startTime DESC
            LIMIT $limit OFFSET $offset";

$sql_count = "SELECT COUNT(DISTINCT app.app_id) FROM appointments app
            $join"
        . " WHERE (is_deleted=0"
        . " AND finishTime < '$today'"
        . " AND is_approved=1)"
        . " OR owner_id=$pk_user OR $cond_is_admin";

$view_data['arr_conf'] = $db->GetAll($sql, $params);
$total_record = (int) $db->GetOne($sql_count, $params);
$view_data['total_page'] = max(array(1, ceil($total_record / $limit)));
$view_data['page'] = (int) get_request_var('page', 1);

View::get_instance()
        ->set_title('Tra cứu cuộc họp cũ')
        ->set_heading('Tra cứu cuộc họp cũ')
        ->set_active_main_nav('history')
        ->set_data($view_data)
        ->render('history');
