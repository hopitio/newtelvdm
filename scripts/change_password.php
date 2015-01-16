<?php

require_login();

$data = array();
if (!empty($_POST))
{
    $db = DB::get_instance();
    $password = get_post_var('txt_password');
    $new_password = get_post_var('txt_new_password');
    $current_password = $db->GetOne("SELECT c_password FROM nt_user WHERE pk_user=?", array(user()->pk_user));

    if ($current_password != hash_password($password))
    {
        $data['error'] = "Sai mật khẩu hiện tại";
    }
    else
    {
        $db->update('nt_user', array('c_password' => hash_password($new_password)), 'pk_user=?', array(user()->pk_user));
        redirect('/login');
    }
}

View::get_instance()
        ->set_heading('Đổi mật khẩu')
        ->set_title('Đổi mật khẩu')
        ->set_data($data)
        ->render('change_password');
