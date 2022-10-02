<?php

namespace Dodie_Coaching\Models;

use PDO;

class Main {
    protected function dbConnect() {
        return new PDO(
            'mysql:host=localhost;port=3308;dbname=dodie;charset=utf8',
            'root',
            'root'
        );
    }
}