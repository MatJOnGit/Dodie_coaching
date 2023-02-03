<?php

namespace App\Domain\Models;

use App\Mixins;
use PDO;

class Subscriber {
    use Mixins\Database;
    
    public function selectSubscribersCount() {
        $db = $this->dbConnect();
        $selectSubscribersCountQuery = "SELECT COUNT(id) as subscribersCount FROM accounts WHERE status = 'subscriber'";
        $selectSubscribersCountStatement = $db->prepare($selectSubscribersCountQuery);
        $selectSubscribersCountStatement->execute();
        
        return $selectSubscribersCountStatement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function dbConnect() {
        return $this->connect();
    }
}