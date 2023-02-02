<?php

namespace App\Domain\Models;

use App\Mixins;
use PDO;

class ProgramFile {
    use Mixins\Database;

    public function selectFileName(string $email) {
        $db = $this->dbConnect();
        $selectFileNameQuery = "SELECT pf.nutrition_file_name FROM program_files pf INNER JOIN accounts acc ON acc.id = pf.user_id WHERE acc.email = ?";
        $selectFileNameStatement = $db->prepare($selectFileNameQuery);
        $selectFileNameStatement->execute([$email]);
        
        return $selectFileNameStatement->fetch();
    }

    public function selectFileStatus(int $subscriberId) {
        $db = $this->dbConnect();
        $selectFileStatusQuery = "SELECT file_status FROM program_files WHERE user_id = ?";
        $selectFileStatusStatement = $db->prepare($selectFileStatusQuery);
        $selectFileStatusStatement->execute([$subscriberId]);
        
        return $selectFileStatusStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function dbConnect() {
        return $this->connect();
    }
}