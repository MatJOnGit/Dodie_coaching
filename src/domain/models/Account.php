<?php

namespace App\Domain\Models;

use App\Mixins;
use PDO;
use PDOException;

final class Account {
    use Mixins\Database;
    
    public function dbConnect() {
        return $this->connect();
    }
    
    /***********************************************************************************
    Tries to add a new account in accounts table with the email and the hashed password.
    If successful, also tries to add a new api token for a guest with the same id.
    If one fails, both requests are reversed.
    ***********************************************************************************/
    public function insertAccount(string $email, string $hashedPassword, string $apiKey) {
        $db = $this->dbConnect();
        
        try {
            $db->beginTransaction();
            
            $insertAccountQuery = "INSERT INTO accounts (email, password) VALUES (?, ?)";
            $insertAccountStatement = $db->prepare($insertAccountQuery);
            $insertAccountStatement->execute([$email, $hashedPassword]);
            
            $accountId = $db->lastInsertId();
            
            $insertTokenQuery = "INSERT INTO api_tokens (id, token, api_permissions) VALUES (?, ?, ?)";
            $insertTokenStatement = $db->prepare($insertTokenQuery);
            $insertTokenStatement->execute([$accountId, $apiKey, 'guest']);
            
            $db->commit();
            
            return true;
        }
        
        catch (PDOException $e) {
            $db->rollBack();
            return false;
        }
    }
    
    public function selectApiKey(string $email) {
        $db = $this->dbConnect();
        $selectApiKeyQuery = "SELECT apit.token FROM accounts acc INNER JOIN api_tokens apit ON acc.id = apit.id WHERE acc.email = ?";
        $selectApiKeyStatement = $db->prepare($selectApiKeyQuery);
        $selectApiKeyStatement->execute([$email]);

        return $selectApiKeyStatement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function selectEmail(string $email) {
        $db = $this->dbConnect();
        $selectEmailQuery = "SELECT email FROM accounts WHERE email = ?";
        $selectEmailStatement = $db->prepare($selectEmailQuery);
        $selectEmailStatement->execute([$email]);
        
        return $selectEmailStatement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function selectId(string $email) {
        $db = $this->dbConnect();
        $selectIdQuery = "SELECT id FROM accounts WHERE email = ?";
        $selectIdStatement = $db->prepare($selectIdQuery);
        $selectIdStatement->execute([$email]);
        
        return $selectIdStatement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function selectPassword (string $email) {
        $db = $this->dbConnect();
        $selectPasswordQuery = "SELECT password FROM accounts WHERE email = ?";
        $selectPasswordStatement = $db->prepare($selectPasswordQuery);
        $selectPasswordStatement->execute([$email]);
        
        return $selectPasswordStatement->fetch();
    }
    
    public function selectRole(string $email) {
        $db = $this->dbConnect();
        $selectRoleQuery = "SELECT status FROM accounts WHERE email = ?";
        $selectRoleStatement = $db->prepare($selectRoleQuery);
        $selectRoleStatement->execute([$email]);
        
        return $selectRoleStatement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function selectSubscribersCount() {
        $db = $this->dbConnect();
        $selectSubscribersCountQuery = "SELECT COUNT(id) as subscribersCount FROM accounts WHERE status = 'subscriber'";
        $selectSubscribersCountStatement = $db->prepare($selectSubscribersCountQuery);
        $selectSubscribersCountStatement->execute();
        
        return $selectSubscribersCountStatement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateLoginDate(string $email) {
        $db = $this->dbConnect();
        $updateLoginDateQuery = "UPDATE accounts SET last_login = NOW() WHERE email = ?";
        $updateLoginDateStatement = $db->prepare($updateLoginDateQuery);
        
        return $updateLoginDateStatement->execute([$email]);
    }
    
    public function updatePassword(string $email, string $hashedPassword) {
        $db = $this->dbConnect();
        $updatePasswordQuery = "UPDATE accounts SET password = ? WHERE email = ?";
        $updatePasswordStatement = $db->prepare($updatePasswordQuery);
        
        return $updatePasswordStatement->execute([$hashedPassword, $email]);
    }
}