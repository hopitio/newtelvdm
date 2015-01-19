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

    //thêm đuôi vào account đã xóa
    $suffix = '__' . uniqid();
    $sql = "UPDATE nt_user SET c_deleted=1, c_account = CONCAT(C_ACCOUNT, '$suffix') WHERE pk_user IN($arr_delete)";
    $db->Execute($sql);

    redirect('/admin/account');
}

$sql = "SELECT * FROM nt_user WHERE c_deleted=0 AND c_is_admin=0 ORDER BY c_sort";

$arr_user = $db->GetAll($sql);

$view = View::get_instance()
        ->set_title('Quản trị tài khoản')
        ->set_heading('Quản trị tài khoản')
        ->set_data(array('arr_user' => $arr_user))
        ->render('admin/account')
;
