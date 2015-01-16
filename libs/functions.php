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
        switch ($mode) {
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
    switch ($mode) {
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
