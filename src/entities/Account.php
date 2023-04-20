<?php

namespace App\Entities;

use App\Domain\Models\Account as AccountModel;

final class Account {
    public function isAccountExisting(array $userData): bool {
        $account = new AccountModel;
        
        $accountPassword = $account->selectPassword($userData['email']);
        $isAccountExisting = false;
        
        if ($accountPassword) {
            $isAccountExisting = password_verify($userData['password'], $accountPassword[0]);
        }
        
        return $isAccountExisting;
    }
    
    public function isEmailExisting(string $email) {
        $account = new AccountModel;
        
        return $account->selectEmail($email);
    }
    
    public function registerAccount(array $userData) {
        $account = new AccountModel;

        $apiKey = $this->_generateApiKey(40);
        
        return $account->insertAccount(
            $userData['email'],
            password_hash($userData['password'], PASSWORD_DEFAULT),
            $apiKey
        );
    }
    
    public function registerPassword(array $userData) {
        $account = new AccountModel;
        
        return $account->updatePassword(
            $_SESSION['email'],
            password_hash($userData['password'], PASSWORD_DEFAULT)
        );
    }
    
    public function updateLoginData(array $userData): bool {
        $account = new AccountModel;
        
        return $account->updateLoginDate($userData['email']);
    }
    
    public function getApiKey(array $userData) {
        $account = new AccountModel;
        
        $apiKey = $account->selectApiKey($userData['email']);
        var_dump($apiKey);
        return $apiKey ? $apiKey['token'] : null;
    }

    private function _generateApiKey($keyLength) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $apiKey = '';
        for ($i = 0; $i < $keyLength; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $apiKey .= $characters[$index];
        }
        return $apiKey;
    }
}