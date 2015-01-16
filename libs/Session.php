<?php

class Session
{

    const NOTIFICATION = '__NOTIFY__';
    const USER = '__USER__';

    /** @var Session */
    static protected $instance;
    static protected $_started = false;

    static function get_instance()
    {
        if (!static::$instance)
        {
            static::$instance = new static;
        }
        return static::$instance;
    }

    function init()
    {
        if (static::$_started)
        {
            return;
        }
        @session_start();
        static::$_started = true;
    }

    function destroy()
    {
        @session_destroy();
        @session_regenerate_id();
        static::$_started = false;
    }

    /**
     * @param type $name
     * @param type $default
     * @return type
     */
    function get($name, $default = null)
    {
        $this->init();
        return isset($_SESSION[$name]) ? $_SESSION[$name] : $default;
    }

    /**
     * @param type $name
     * @param type $value
     */
    function set($name, $value)
    {
        $this->init();
        $_SESSION[$name] = $value;
    }

    /**
     * Xóa một biến
     * @param type $name
     */
    function unset_var($name)
    {
        if (isset($_SESSION[$name]))
        {
            unset($_SESSION[$name]);
        }
    }

    function get_token()
    {
        return md5(session_id());
    }

    /**
     * @param type $status
     * @param type $text
     */
    function notify($status, $text)
    {
        $this->set(static::NOTIFICATION, array('text' => $text, 'status' => $status));
    }

    function get_notification()
    {
        $notify = static::get(static::NOTIFICATION, false);
        $this->set(static::NOTIFICATION, false);
        return $notify;
    }

    function set_current_user($user)
    {
        $this->set(static::USER, $user);
    }

    /** @return User */
    function get_current_user()
    {
        return $this->get(static::USER);
    }

}
