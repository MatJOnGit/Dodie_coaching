<?php

namespace Dodie_Coaching\Models;

use PDO;

class ResetToken extends Main {
    public function deleteToken(string $email) {
        $db = $this->dbConnect();
        $deleteTokenQuery = "DELETE rt FROM reset_tokens rt INNER JOIN accounts acc ON rt.user_id = acc.id WHERE acc.email = ?";
        $deleteTokenStatement = $db->prepare($deleteTokenQuery);
        
        return $deleteTokenStatement->execute([$email]);
    }
    
    public function insertToken(string $token, string $email) {
        $db = $this->dbConnect();
        $insertTokenQuery = "INSERT INTO reset_tokens (token, user_id) VALUES ((?), (SELECT id FROM accounts WHERE email = ?))";
        $insertTokenStatement = $db->prepare($insertTokenQuery);
        
        return $insertTokenStatement->execute([$token, $email]);
    }
    
    public function selectRemainingAttempts(string $email) {
        $db = $this->dbConnect();
        $selectRemainingAttemptsQuery = "SELECT remaining_atpt FROM reset_tokens rt INNER JOIN accounts acc ON rt.user_id = acc.id WHERE acc.email = ?";
        $selectRemainingAttemptsStatement = $db->prepare($selectRemainingAttemptsQuery);
        $selectRemainingAttemptsStatement->execute([$email]);
        
        return $selectRemainingAttemptsStatement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function selectToken(string $email) {
        $db = $this->dbConnect();
        $selectTokenQuery = "SELECT token FROM reset_tokens rt INNER JOIN accounts acc ON rt.user_id = acc.id WHERE acc.email = ?";
        $selectTokenStatement = $db->prepare($selectTokenQuery);
        $selectTokenStatement->execute([$email]);
        
        return $selectTokenStatement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function selectTokenDate(string $email) {
        $db = $this->dbConnect();
        $selectTokenDateQuery = "SELECT generation_date FROM reset_tokens rt INNER JOIN accounts acc ON rt.user_id = acc.id WHERE acc.email = ?";
        $selectTokenDateStatement = $db->prepare($selectTokenDateQuery);
        $selectTokenDateStatement->execute([$email]);
        
        return $selectTokenDateStatement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateRemainingAttempts(string $email) {
        $db = $this->dbConnect();
        $updateRemainingAttemptsQuery = "UPDATE reset_tokens rt INNER JOIN accounts acc ON rt.user_id = acc.id SET rt.remaining_atpt = rt.remaining_atpt - 1 WHERE acc.email = ?";
        $updateRemainingAttemptsStatement = $db->prepare($updateRemainingAttemptsQuery);
        
        return $updateRemainingAttemptsStatement->execute([$email]);
    }
}