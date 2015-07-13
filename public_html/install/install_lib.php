<?php

class install_lib
{

    static $db;

    /**
     * 
     * @param type $user
     * @param type $password
     * @param type $db
     */
    static function connect_db($db_type, $user, $password, $db)
    {
        require_once BASE_DIR . 'libs/adodb5/adodb.inc.php';
        static::$db = NewADOConnection($db_type);
        static::$db->debug = 1;
        if (!static::$db->Connect('localhost', $user, $password, $db))
        {
            die('Không kết nối dc CSDL');
        }
        static::$db->SetCharSet('utf8');
        static::$db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    /** @return ADOConnection */
    static function DB()
    {
        return static::$db;
    }

    static function reset_db()
    {
        $db = static::DB();
        $db->Execute("DROP TABLE nt_user");
        $db->Execute("DROP TABLE nt_attendiees");
        $db->Execute("ALTER TABLE appointments DROP COLUMN is_approved");
        $db->Execute("ALTER TABLE appointments DROP COLUMN owner_id");
    }

    static function setup_db()
    {
        $db = static::DB();
        static::reset_db();

        $db->StartTrans();
        $db->Execute("CREATE TABLE nt_user(
                        pk_user INT PRIMARY KEY AUTO_INCREMENT,
                        c_name VARCHAR(255),
                        c_representer VARCHAR(255),
                        c_email VARCHAR(255),
                        c_phone_no VARCHAR(20),
                        c_password VARCHAR(255),
                        c_deleted TINYINT DEFAULT 0,
                        c_sort INT,
                        c_is_admin TINYINT DEFAULT 0,
                        c_account VARCHAR(255)
                    )"
        );
        $db->Execute("CREATE UNIQUE INDEX unq_account ON nt_user(c_account)");
        $db->Execute("ALTER TABLE appointments ADD is_approved TINYINT DEFAULT 0");
        $db->Execute("ALTER TABLE appointments ADD owner_id INT DEFAULT 1");
        $db->Execute("ALTER TABLE appointments ADD decline_reason TEXT");
        $db->Execute("CREATE TABLE nt_attendiees(
                        fk_appointment INT NOT NULL,
                        fk_user INT NOT NULL
                    )");
        $db->Execute("CREATE UNIQUE INDEX unq_att ON nt_attendiees(fk_appointment, fk_user)");

        $db->Execute("
            INSERT INTO  nt_user (
                pk_user ,
                c_name ,
                c_representer ,
                c_email ,
                c_phone_no ,
                c_password ,
                c_deleted ,
                c_sort ,
                c_is_admin ,
                c_account
            )
            VALUES (
                NULL ,  'Quản trị hệ thống',  'Quản trị hệ thống',  'admin@gmail.com',  '01666244670',  'e10adc',  '0',  '1',  '1',  'admin'
            );
            ");
        
        if (!$db->CompleteTrans())
        {
            die('Khởi tạo CSDL thất bại');
        }
    }

}
