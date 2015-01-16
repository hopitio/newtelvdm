<?php

class View
{

    protected static $instance;
    protected $layout = 'default_layout';
    protected $title = 'Hội nghị truyền hình Newtel';
    protected $view_data = array();
    protected $main_nav = array();
    protected $active_main_nav;
    protected $heading;

    function __construct()
    {
        if (user()->is_logged)
        {
            $this->add_main_nav('schedule', 'Lịch họp', site_url('/index'))
                    ->add_main_nav('history', 'Tra cứu cuộc họp cũ', site_url('/history'));
        }
    }

    /** @return View */
    static function get_instance()
    {
        if (!static::$instance)
        {
            static::$instance = new static;
        }
        return static::$instance;
    }

    function set_layout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    function set_title($title)
    {
        $this->title = $title;
        return $this;
    }

    function set_heading($heading)
    {
        $this->heading = $heading;
        return $this;
    }

    function set_data($arr)
    {
        $this->view_data += $arr;
        return $this;
    }

    /**
     * @param type $key
     * @param type $label
     * @param type $url
     */
    function add_main_nav($key, $label, $url)
    {
        $this->main_nav[$key] = array('label' => $label, 'url' => $url);
        return $this;
    }

    function set_active_main_nav($key)
    {
        $this->active_main_nav = $key;
        return $this;
    }

    function render($name)
    {
        $template = BASE_DIR . 'templates/' . $name . '.tpl.php';
        foreach ($this->view_data as $k => $v)
        {
            $$k = $v;
        }

        if (!$this->layout)
        {
            require $template;
            return;
        }

        $layout = BASE_DIR . 'templates/layout/' . $this->layout . '.tpl.php';
        if (!file_exists($layout))
        {
            throw new Exception("Không có layout $layout");
        }

        $cached_dir = BASE_DIR . 'cache/layout/';
        $cached_header = $cached_dir . $this->layout . '.header.php';
        $cached_footer = $cached_dir . $this->layout . '.footer.php';

        if (!is_dir($cached_dir) && !mkdir($cached_dir))
        {
            throw new Exception("Không tạo được $cached_dir");
        }

        $time_layout = filemtime($layout);

        if (!file_exists($cached_header) || !file_exists($cached_footer) || filemtime($cached_header) < $time_layout || filemtime($cached_header) < $time_layout)
        {
            $html_layout = file_get_contents($layout);
            $pos = strpos($html_layout, '{{LAYOUT_CONTENT}}');
            if ($pos === false)
            {
                throw new Exception("file $layout phải có {{LAYOUT_CONTENT}}");
            } $html_header = substr($html_layout, 0, $pos);
            $html_footer = substr($html_layout, $pos + strlen('{{LAYOUT_CONTENT}}') + 1);
            if (!file_put_contents($cached_header, $html_header) || !file_put_contents($cached_footer, $html_footer))
            {
                throw new Exception("Ghi file thất bại");
            }
        }



        require $cached_header;
        require $template;
        require $cached_footer;
    }

}
