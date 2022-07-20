<?php

class Manager {
    protected function dbConnect() {
        $db = new PDO(
            'mysql:host=localhost;port=3308;dbname=dodie;charset=utf8',
            'root',
            'root'
        );

        return $db;
    }
}