<?php

class DB
{

    /** @var static */
    static protected $_instance;

    /** @var ADOConnection */
    protected $_ado_connection;

    /** @var DB_Config */
    static protected $_config;
    protected $cached_date;

    static function config(DB_Config $config)
    {
        static::$_config = $config;
    }

    function __construct()
    {
        $config = static::$_config;
        if (!$config)
        {
            throw new SP_Exception('Phải DB::config() trước khi DB::get_instance()');
        }
        $this->_ado_connection = NewADOConnection($config->type);
        $this->_ado_connection->debug = $config->debug;
        $status = $this->_ado_connection->Connect(
                $config->host
                , $config->user
                , $config->password
                , $config->database_name);
        if (!$status)
        {
            throw new Database_Connection_Exception("Không kết nối được CSDL");
        }
        global $ADODB_CACHE_DIR;
        $ADODB_CACHE_DIR = $config->cache_dir;

        $this->_ado_connection->cacheSecs = $config->cache_sec;
        $this->_ado_connection->SetCharSet('utf8');
        $this->_ado_connection->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    /** @return ADOConnection */
    static public function get_instance()
    {
        if (static::$_instance == null)
        {
            static::$_instance = new static;
        }
        return static::$_instance;
    }

    function __call($name, $arguments)
    {
        $callback = array($this->_ado_connection, $name);
        if (!is_callable($callback))
        {
            throw new Exception('Không có DB::' . $name);
        }
        return call_user_func_array($callback, $arguments);
    }

    function __get($name)
    {
        if (isset($this->_ado_connection->{$name}))
        {
            return $this->_ado_connection->{$name};
        }
    }

    function __set($name, $value)
    {
        $this->_ado_connection->{$name} = $value;
    }

    function get_date($cache = true)
    {
        if (!$this->cached_date || !$cache)
        {
            $this->cached_date = $this->GetOne("SELECT NOW()");
        }
        return $this->cached_date;
    }

    /**
     * 
     * @param string $table Tên bảng
     * @param string $where Điều kiện
     * @param array $params Tham số
     * @return int Số bản ghi xóa hoặc FALSE
     */
    public function delete($table, $where, $params = array())
    {
        $sql = "Delete From $table Where $where";
        $result = $this->Execute($sql, $params);
        if ($result == false)
        {
            return false;
        }
        else
        {
            return $this->Affected_Rows();
        }
    }

    /**
     * Insert vào CSDL
     * @param string $table Tên bảng
     * @param array $arr_data Mảng dữ liệu
     * @param string $pk Tên trường khóa chính
     * @return int ID vừa tạo hoặc FALSE nếu thất bại 
     * @throws InvalidArgumentException
     */
    public function insert($table, $arr_data, $pk = null)
    {
        if (!is_array($arr_data))
        {
            throw new InvalidArgumentException('$arr_data Phải là array $k=>$v');
        }
        if (empty($arr_data))
        {
            throw new InvalidArgumentException('$arr_data Không được empty()');
        }
        $sql = "Insert Into $table(" . implode(',', array_keys($arr_data)) . ") Values(?" . str_repeat(',?', count($arr_data) - 1) . ")";
        $this->Execute($sql, array_values($arr_data));
        return $this->Insert_ID($table, $pk);
    }

    /**
     * Insert nhiều row bằng một lệnh SQL
     * @param string $table
     * @param array $arr_data Mảng 2 chiều
     * @throws InvalidArgumentException
     */
    function insert_many($table, $arr_data)
    {
        if (!is_array($arr_data))
        {
            throw new InvalidArgumentException('$arr_data Phải là array $k=>$v');
        }
        if (empty($arr_data))
        {
            throw new InvalidArgumentException('$arr_data Không được empty()');
        }
        $first_row = $arr_data[0];
        $fields = implode(",", array_keys($first_row));
        $params = array();
        $count_fields = count($first_row);
        $sql = "INSERT INTO $table($fields) VALUES(?" . str_repeat(",?", $count_fields - 1) . ")";
        $sql .= str_repeat(",(?, " . str_repeat(",?", $count_fields - 1) . ")", count($arr_data) - 1);
        foreach ($arr_data as $row)
        {
            $params = array_merge($params, array_values($row));
        }
        $this->Execute($sql, $params);
    }

    /**
     * Update CSDL
     * @param string $table Tên bảng
     * @param array $arr_data Mảng dữ liệu
     * @param string $where Điều kiện. VD: '1=1'
     * @param array $params Mảng tham số
     * @return int Số bản ghi Update hoặc FALSE
     * @throws Exception
     */
    public function update($table, $arr_data, $where, $where_params = array())
    {
        if (!is_array($arr_data))
        {
            throw new InvalidArgumentException('$arr_data Phải là array $k=>$v');
        }
        if (empty($arr_data))
        {
            throw new InvalidArgumentException('$arr_data Không được empty()');
        }
        $sql = '';
        $params = array();
        foreach ($arr_data as $k => $v)
        {
            $sql .= strlen($sql) > 0 ? ",$k=?" : "Update $table Set $k=?";
            array_push($params, $v);
        }
        $sql .= " Where $where";
        $result = $this->Execute($sql, array_merge($params, $where_params));
        if ($result == false)
        {
            return false;
        }
        else
        {
            return $this->Affected_Rows();
        }
    }

    /**
     * @param string $table
     * @param string $pk_col
     * @param string $order_col
     * @param string $where
     * @param string $where_params
     */
    public function rebuild_order($arr_opts)
    {
        $db = $this;
        //xử lý tham số
        $v_table = $arr_opts['table'];
        $v_pk_col = $arr_opts['pk_col'];
        $v_order_col = $arr_opts['order_col'];
        $v_where = isset($arr_opts['where']) ? $arr_opts['where'] : '1=1';
        $arr_where_params = isset($arr_opts['where_params']) ? $arr_opts['where_params'] : array();
        //lấy danh sách cần update
        $query = Query::make()
                ->select($v_pk_col)
                ->from($v_table)
                ->order_by($v_order_col);
        if ($v_where)
        {
            $query->where($v_where);
        }
        $arr_items = $db->GetCol($query, $arr_where_params);
        //update
        foreach ($arr_items as $v_index => $v_id)
        {
            $this->update($v_table, array($v_order_col => $v_index + 1), "{$v_pk_col}={$v_id}");
        }
    }

    /**
     * @param string $table
     * @param string $pk_col
     * @param string $parent_col
     * @param string $base_col cột này để đưa vào path, có thể là order|sort|pk
     * @param boolean $rebuild_order default FALSE
     * @param string $path_col
     * @param int $start_node
     * @param string $where
     * @param string $where_params
     */
    public function rebuild_path($arr_opts)
    {
        $db = $this;
        //xử lý tham số
        $v_table = fetch_array($arr_opts, 'table');
        $v_pk_col = fetch_array($arr_opts, 'pk_col');
        $v_parent_col = fetch_array($arr_opts, 'parent_col');
        $v_base_col = fetch_array($arr_opts, 'base_col');
        $v_rebuild_order = fetch_array($arr_opts, 'rebuild_order', false);
        $v_internal_order_col = fetch_array($arr_opts, 'path_col');
        $v_start_node = fetch_array($arr_opts, 'start_node', FALSE);
        $v_where = fetch_array($arr_opts, 'where', '1=1');
        $arr_where_params = fetch_array($arr_opts, 'where_params', array());

        if ($v_start_node !== FALSE)
        {
            $v_root_conds = $v_start_node === NULL ?
                    $v_where . " AND $v_pk_col IS NULL" :
                    $v_where . " AND $v_pk_col = $v_start_node";
        }
        else
        {
            $v_root_conds = $v_where . " AND ($v_parent_col IS NULL Or $v_parent_col < 1)";
            if ($v_rebuild_order)
            {
                //order root
                $this->rebuild_order(array(
                    'table'     => $v_table,
                    'pk_col'    => $v_pk_col,
                    'order_col' => $v_base_col,
                    'where'     => $v_root_conds,
                    $arr_where_params
                ));
            }
            //update root internal order
            $db->Execute("UPDATE $v_table SET $v_internal_order_col = " . $db->Concat($v_base_col, "'/'")
                    . " WHERE $v_root_conds");
        }
        //query root stack
        $query = Query::make()
                ->select("$v_pk_col, $v_base_col, $v_internal_order_col")
                ->from($v_table)
                ->where($v_root_conds);
        $arr_stack = $db->GetAll($query, $arr_where_params);
        if (empty($arr_stack))
        {
            return;
        }
        //duyet stack
        do
        {
            //parent
            $arr_single = array_shift($arr_stack);
            $v_id = $arr_single[$v_pk_col];
            $v_internal_order = $arr_single[$v_internal_order_col];
            if ($v_rebuild_order)
            {
                //order chilren
                $this->rebuild_order(array(
                    'table'     => $v_table,
                    'pk_col'    => $v_pk_col,
                    'order_col' => $v_base_col,
                    'where'     => $v_where . " AND $v_parent_col = $v_id",
                    $arr_where_params
                ));
            }
            //update internal order
            $db->Execute("UPDATE $v_table SET $v_internal_order_col = " . $db->Concat("'$v_internal_order'", 'C_ORDER', "'/'")
                    . " WHERE $v_where AND $v_parent_col = $v_id");
            //query children
            $query = Query::make()
                    ->select("$v_pk_col, $v_base_col, $v_internal_order_col")
                    ->from($v_table)
                    ->where($v_where . " AND $v_parent_col = $v_id")
                    ->order_by($v_base_col);
            $arr_children = $db->GetAll($query, $arr_where_params);
            //push stack
            $arr_stack = array_merge($arr_stack, $arr_children);
        }
        while (!empty($arr_stack));
    }

    /**
     * @var $table 
     * @var $pk_col 
     * @var $order_col
     * @var $pk_value Mã của đối tượng được swap
     * @var $mode 'up' hoặc 'down'
     */
    function swap_order($arr_opts)
    {
        $table = fetch_array($arr_opts, 'table');
        $pk_col = fetch_array($arr_opts, 'pk_col');
        $pk_value = fetch_array($arr_opts, 'pk_value');
        $order_col = fetch_array($arr_opts, 'order_col');
        $mode = fetch_array($arr_opts, 'mode');

        if ($mode == 'up')
        {
            $mode = '<';
        }
        elseif ($mode == 'down')
        {
            $mode = '>';
        }
        else
        {
            //throw
        }
        $current_order = (int) $this->GetOne(Query::make()->select($order_col)->from($table)->where("$pk_col=$pk_value"));
        $affected_record = (int) $this->GetOne(Query::make()->select($pk_col)->from($table)->where("$order_col $mode $current_order"));
        $this->update($table, array($order_col => $mode == '>' ? $current_order + 1 : $current_order - 1), "$pk_col=$pk_value");
        $this->update($table, array($order_col => $current_order), "$pk_col=$affected_record");
    }

}
