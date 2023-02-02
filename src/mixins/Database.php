<?php

namespace App\Mixins;

use PDO;

trait Database {
    private $_hostname = 'localhost';

    private $_port = 3308;

    private $_dbname = 'dodie';

    private $_charset = 'utf8';

    public function connect() {
        return new PDO(
            'mysql:host=' . $this->_hostname . ';port=' . $this->_port . ';dbname=' . $this->_dbname . ';charset=' . $this->_charset,
            'root',
            'root'
        );
    }
}