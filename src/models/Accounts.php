<?php

namespace Dodie_Coaching\Models;

use PDO;

class Accounts extends Main {
    public function insertAccount(string $email, string $hashedPassword) {
        $db = $this->dbConnect();
        $insertAccountQuery = "INSERT INTO accounts (email, password) VALUES (:email, :password)";
        $insertAccountStatement = $db->prepare($insertAccountQuery);
        
        return $insertAccountStatement->execute([
            'email' => $email,
            'password' => $hashedPassword
        ]);
    }
    
    public function selectPassword (string $email) {
        $db = $this->dbConnect();
        $selectPasswordQuery = "SELECT password FROM accounts WHERE email = ?";
        $selectPasswordStatement = $db->prepare($selectPasswordQuery);
        $selectPasswordStatement->execute([$email]);
        
        return $selectPasswordStatement->fetch();
    }
    
    public function selectEmail(string $email) {
        $db = $this->dbConnect();
        $selectEmailQuery = "SELECT email FROM accounts WHERE email = ?";
        $selectEmailStatement = $db->prepare($selectEmailQuery);
        $selectEmailStatement->execute([$email]);
        
        return $selectEmailStatement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function selectRole(string $email) {
        $db = $this->dbConnect();
        $selectRoleQuery = "SELECT status FROM accounts WHERE email = ?";
        $selectRoleStatement = $db->prepare($selectRoleQuery);
        $selectRoleStatement->execute([$email]);
        
        return $selectRoleStatement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateLoginDate(string $email) {
        $db = $this->dbConnect();
        $updateLoginDateQuery = "UPDATE accounts SET last_login = NOW() WHERE email = ?";
        $updateLoginDateStatement = $db->prepare($updateLoginDateQuery);
        
        return $updateLoginDateStatement->execute([$email]);
    }
    
    public function updatePassword($email, $hashedPassword) {
        $db = $this->dbConnect();
        $updatePasswordQuery = "UPDATE accounts SET password = ? WHERE email = ?";
        $updatePasswordStatement = $db->prepare($updatePasswordQuery);
        
        return $updatePasswordStatement->execute([$hashedPassword, $email]);
    }
}