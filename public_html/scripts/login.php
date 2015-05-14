<?php

$session = Session::get_instance();
$session->destroy();
$session->init();
$session->set_current_user(null);
$view = View::get_instance();

if ($_POST)
{
    $db = DB::get_instance();
    $account = get_post_var('txt_account');
    $pass = get_post_var('txt_pass');

    $user_data = $db->GetRow("SELECT * FROM nt_user WHERE c_account=? AND c_password=? AND c_deleted=0", array($account, hash_password($pass)));
    if (empty($user_data))
    {
        $view->set_data(array(
            'error' => 'Sai tài khoản hoặc mật khẩu'
        ));
    }
    else
    {
        //lưu thông tin đăng nhập
        $session->init();
        $user = new User;
        $user->email = $user_data['c_email'];
        $user->is_logged = true;
        $user->name = $user_data['c_name'];
        $user->phone_no = $user_data['c_phone_no'];
        $user->pk_user = $user_data['pk_user'];
        $user->representer = $user_data['c_representer'];
        $user->is_admin = $user_data['c_is_admin'];
        $user->account = $user_data['c_account'];

        $session->set_current_user($user);

        //sync vs videomost
        sync_vdm_user($account);

        $goback = get_request_var('goback');
        if ($user->is_admin && (!$goback || $goback == 'index'))
        {
            redirect('/admin/approve');
        }
        else if ($goback == 'index')
        {
            redirect('/');
        }
        else
        {
            redirect($goback);
        }
    }
}

$view->set_title('Đăng nhập hội nghị truyền hình')
        ->set_heading('Hội nghị truyền hình trực tuyến')
        ->render('login');

