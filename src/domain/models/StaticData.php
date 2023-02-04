<?php

namespace App\Domain\Models;

use App\Mixins;

final class StaticData {
    use Mixins\Database;

    public function insertStaticData(string $email) {
        $db = $this->dbConnect();
        $insertStaticDataQuery = "INSERT INTO users_static_data (user_id) VALUES ((SELECT id FROM accounts WHERE email = ?))";
        $insertStaticDataStatement = $db->prepare($insertStaticDataQuery);
        
        return $insertStaticDataStatement->execute([$email]);
    }

    public function dbConnect() {
        return $this->connect();
    }
}