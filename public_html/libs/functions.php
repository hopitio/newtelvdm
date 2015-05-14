<?php

/**
 * Loại bỏ ký tự đặc biệt
 * @param string $str
 * @return string
 */
function escape_string($str)
{
    $arr_search = array('&', '<', '>', '"', "'", '/', "\\");
    $arr_replace = array();
    foreach ($arr_search as $v)
    {
        $arr_replace[] = htmlentities($v);
    }
    return str_replace($arr_search, $arr_replace, $str);
}

/**
 * Lấy dữ liệu từ array
 * @param array $array
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function fetch_array($array, $key, $default = NULL)
{
    return isset($array[$key]) ? $array[$key] : $default;
}

        const XPATH_ARRAY = 1;
        const XPATH_OBJECT = 2;
        const XPATH_STRING = 3;

/**
 * Không tìm thấy cũng trả về dữ liệu, tránh lỗi lập trình
 * @param SimpleXMLElement $simplexml
 * @param string $xpath
 * @param int $mode
 * @param mixed $default
 * @return \SimpleXMLElement[]
 */
function xpath($simplexml, $xpath, $mode = XPATH_ARRAY, $default = null)
{
    if ($simplexml == false) //validate
    {
        switch ($mode)
        {
            case XPATH_ARRAY:
                return array();
            case XPATH_OBJECT:
                return new SimpleXMLElement;
            case XPATH_STRING:
                return $default;
            default:
                throw new Exception('$mode Phải dùng CONST. VD: XPATH_ARRAY');
        }
    }

    $arr_results = $simplexml->xpath($xpath);
    switch ($mode)
    {
        case XPATH_ARRAY:
            return $arr_results;
        case XPATH_OBJECT:
            return isset($arr_results[0]) ? $arr_results[0] : new SimpleXMLElement;
        case XPATH_STRING:
            return isset($arr_results[0]) ? strval($arr_results) : $default;
        default:
            throw new Exception('$mode Phải dùng CONST. VD: XPATH_ARRAY');
    }
}

/**
 * 
 * @param string $name
 * @param mixed $default
 * @param bool $escape
 * @return mixed
 */
function get_request_var($name, $default = NULL, $escape = true)
{
    $var = isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
    if (!$escape)
    {
        return $var;
    }
    if (!is_array($var) && $var !== NULL)
    {
        return escape_string($var);
    }
    else //array
    {
        if (is_array($var))
        {
            array_walk_recursive($var, 'escape_string');
        }
        return $var;
    }
}

/**
 * 
 * @param string $name
 * @param mixed $default
 * @param bool $escape
 * @return mixed
 */
function get_post_var($name, $default = NULL, $escape = true)
{
    $var = isset($_POST[$name]) ? $_POST[$name] : $default;
    if (!$escape)
    {
        return $var;
    }
    if (!is_array($var) && $var !== NULL)
    {
        return escape_string($var);
    }
    else //array
    {
        if (is_array($var))
        {
            array_walk_recursive($var, 'escape_string');
        }
        return $var;
    }
}

/**
 * @param string $uri_string
 */
function site_url($uri_string = null, $request_params = array())
{
    $url = SITE_URL . trim($uri_string, "/\\") . '/';
    if (!empty($request_params))
    {
        $sep = '?';
        foreach ($request_params as $k => $v)
        {
            $url.= "{$sep}{$k}={$v}";
            $sep = '&';
        }
    }
    return $url;
}

/** @return User */
function user()
{
    $user = Session::get_instance()->get_current_user();
    if (!$user)
    {
        $user = new User;
    }
    return $user;
}

/**
 * 
 * @param type $uri
 * @param type $params
 */
function redirect($uri, $params = array())
{
    $url = site_url($uri, $params);
    header('Location: ' . $url);
    exit;
}

function get_current_url()
{
    return $_SERVER['REQUEST_URI'];
}

function get_current_uri()
{
    global $URI;
    return $URI;
}

/**
 * 
 * @param bool $ajax
 */
function require_login($is_ajax = false)
{
    if (!user()->is_logged)
    {
        if (!$is_ajax)
        {
            $goback = get_current_uri();
            redirect('login', array('goback' => $goback));
        }
        else
        {
            header('HTTP/1.0 401 Unauthorized');
            die;
        }
    }
}

function forbidden()
{
    header('HTTP/1.0 403 Forbidden');
    echo '<h1>403 Không có quyền truy cập</h1>';
    echo 'Bạn không có quyền truy cập trang này.';
    die;
}

function hash_password($string)
{
    return substr(md5($string), 0, strlen($string));
}

function is_conference_started($confid, $is_stop = false)
{
    $db = DB::get_instance();
    $sql = 'SELECT * FROM confroomscontrol WHERE `id`="' . $confid . '"';
    if ($r = $db->GetRow($sql))
    {
        if (empty($r['status']))
            return false;
        if ($r['status'] == 1)
            return true;
    }
    elseif (!$is_stop)
    {
        $sql = 'INSERT INTO confroomscontrol SET `id`="' . $confid . '"';
        $db->Execute($sql);
        return false;
    }
    return false;
}

function sms_url($arr_user_id, $arr_user_selected, $app_id = null)
{
    $request_params = array(
        'user'   => implode(',', $arr_user_id),
        'app_id' => $app_id
    );

    if (!empty($arr_user_selected))
    {
        $request_params['selected_user'] = implode(',', $arr_user_selected);
    }

    return site_url('/sms', $request_params);
}

/** chuyển tiếng việt có dấu -> khong dau */
function tieng_viet_khong_dau($str)
{
    $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", "a", $str);
    $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", "e", $str);
    $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", "i", $str);
    $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", "o", $str);
    $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", "u", $str);
    $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", "y", $str);
    $str = preg_replace("/(đ)/", "d", $str);
    $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", "A", $str);
    $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", "E", $str);
    $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", "I", $str);
    $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", "O", $str);
    $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", "U", $str);
    $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", "Y", $str);
    $str = preg_replace("/(Đ)/", "D", $str);

    return $str;
}

function sync_vdm_user($account)
{
    $db = DB::get_instance();
    $arr_account = $db->GetRow("SELECT * FROM nt_user WHERE c_account=?", array($account));
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
            'email'                => $arr_account['c_email'],
            'password'             => md5('123456'),
            'lastname'             => $arr_account['c_name'],
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
}
