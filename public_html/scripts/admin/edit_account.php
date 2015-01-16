<?php

require_login();
if (!user()->is_admin)
{
    forbidden();
}


$user_id = (int) get_request_var('id');
$db = DB::get_instance();
$user_data = $db->GetRow("SELECT * FROM nt_user WHERE pk_user=?", array($user_id));

if (!empty($_POST))
{
    $update_data = array(
        'c_name'        => get_post_var('txt_name'),
        'c_phone_no'    => get_post_var('txt_phone'),
        'c_email'       => get_post_var('txt_email'),
        'c_account'     => get_post_var('txt_account'),
        'c_representer' => get_post_var('txt_representer')
    );

    $password = get_post_var('txt_password');
    if ($password != fetch_array($user_data, 'c_password'))
    {
        $update_data['c_password'] = hash_password($password);
    }

    if ($user_id)
    {
        $db->update('nt_user', $update_data, 'pk_user=?', array($user_id));
    }
    else
    {
        $max_sort = (int) $db->GetOne("SELECT MAX(c_sort) FROM nt_user");
        $update_data['c_sort'] = $max_sort + 1;
        $user_id = $db->insert('nt_user', $update_data);
    }
    //redirect('/admin/account');
}


View::get_instance()
        ->set_title('Sửa tài khoản')
        ->set_heading('Sửa tài khoản')
        ->set_data(array('user_data' => $user_data))
        ->render('/admin/edit_account');



