<?php

require_login();
if (!user()->is_admin)
{
    forbidden();
}

$db = DB::get_instance();

if (get_post_var('btn_sort'))
{
    $arr_sort = get_post_var('txt_sort', array());
    foreach ($arr_sort as $user_id => $sort_value)
    {
        $db->update('nt_user', array('c_sort' => $sort_value), 'pk_user=?', array($user_id));
    }
    redirect('/admin/account');
}
else if (get_post_var('btn_delete'))
{
    $arr_delete = get_post_var('check', array(0));
    $arr_delete = escape_string(implode(',', $arr_delete));

    foreach (get_post_var('check', array(0)) as $delete_id)
    {
        $delete_acc = $db->GetOne("SELECT c_account FROM nt_user WHERE pk_user=?", array($delete_id));
        //xóa acc của videomost
        $db->delete('accounts', 'login=?', array($delete_acc));
    }
    //thêm đuôi vào account đã xóa
    $suffix = '__' . uniqid();
    $sql = "UPDATE nt_user SET c_deleted=1, c_account = CONCAT(C_ACCOUNT, '$suffix') WHERE pk_user IN($arr_delete)";
    $db->Execute($sql);

    redirect('/admin/account');
}


$cond = "c_deleted=0 AND c_is_admin=0";
$params = array();
$search = get_post_var('search');
if ($search)
{
    $cond.= " AND (c_name LIKE ? OR c_representer LIKE ? OR c_email LIKE ? OR c_phone_no LIKE ? OR c_account LIKE ?)";
    $params = array_merge($params, array(
        "%$search%", "%$search%", "%$search%", "%$search%", "%$search%"
    ));
}
$sql = "SELECT * FROM nt_user WHERE $cond ORDER BY c_sort";

$arr_user = $db->GetAll($sql, $params);

$view = View::get_instance()
        ->set_title('Quản trị tài khoản')
        ->set_heading('Quản trị tài khoản')
        ->set_data(array('arr_user' => $arr_user))
        ->set_active_main_nav('account')
        ->render('admin/account')
;
