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
        $vdm_user = $db->GetOne("SELECT uid FROM users WHERE login=?", array($account));
        if (!$vdm_user) //nếu user videmost chưa tồn tại
        {

            //tim tariffs auto
            $policy_id = $db->GetOne("SELECT policy_id FROM tariffs WHERE policy_name=?", array('auto'));
            //tao account
            if ($policy_id)
            {
                $account_id = $db->GetOne("SELECT MAX(account_id) + 1 FROM accounts");
                $db->insert('accounts', array(
                    'account_id' => $account_id,
                    'policy_id'  => $policy_id,
                    'created_dt' => DateTimeEx::create()->toIsoString()
                ));
            }

            $db->insert('users', array(
                'login'                => $account,
                'realmname'            => '',
                'account_id'           => $account_id,
                'email'                => $user->email,
                'password'             => md5('123456'),
                'lastname'             => $user->name,
                'createDate'           => DateTimeEx::create()->toIsoString(),
                'modificationDate'     => DateTimeEx::create()->toIsoString(),
                'passmodificationDate' => DateTimeEx::create()->toIsoString(),
                'blocked'              => 0,
                'approved'             => 1,
                'referer'              => 'registration',
                'xmpp_created'         => 0,
                'status'               => 0,
                'timezone'             => 7,
                'lang'                 => 'en',
                'loglevel'             => 0
            ));
        }

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

