<?php

namespace Dodie_Coaching\Models;

use PDO;

class StaticData extends Main {
    public function insertStaticData(string $email) {
        $db = $this->dbConnect();
        $insertStaticDataQuery = "INSERT INTO users_static_data (user_id) VALUES ((SELECT id FROM accounts WHERE email = ?))";
        $insertStaticDataStatement = $db->prepare($insertStaticDataQuery);

        return $insertStaticDataStatement->execute([$email]);
    }
}