<?php

namespace App\Domain\Models;

use App\Mixins;
use PDO;

class Appliance {
    use Mixins\Database;

    public function selectAppliancesCount() {
        $db = $this->dbConnect();
        $selectAppliancesCountQuery = "SELECT COUNT(user_id) as appliancesCount FROM appliances WHERE staging = 'support_confirmation'";
        $selectAppliancesCountStatement = $db->prepare($selectAppliancesCountQuery);
        $selectAppliancesCountStatement->execute();
        
        return $selectAppliancesCountStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function dbConnect() {
        return $this->connect();
    }
}