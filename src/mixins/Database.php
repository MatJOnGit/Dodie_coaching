<?php

namespace App\Mixins;

use PDO;

trait Database
{
    private $_charset = 'utf8';
    private $_dbname;
    private $_hostname;
    private $_port = 3306;
    private $_pwd;

    public function __construct()
    {
        $this->_hostname = getenv('MYSQL_HOSTNAME');
        $this->_dbname = getenv('MYSQL_DATABASE');
        $this->_pwd = getenv('MYSQL_ROOT_PASSWORD');
    }

    public function connect()
    {
        return new PDO(
            'mysql:host=' . $this->_hostname . ';port=' . $this->_port . ';dbname=' . $this->_dbname . ';charset=' . $this->_charset,
            'root',
            $this->_pwd
        );
    }
}
