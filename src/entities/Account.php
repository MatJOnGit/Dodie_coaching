<?php

namespace App\Entities;

use App\Domain\Models\Account as AccountModel;

final class Account {
    public function registerAccount(array $userData) {
        $account = new AccountModel;
        
        return $account->insertAccount(
            $userData['email'],
            password_hash($userData['password'], PASSWORD_DEFAULT)
        );
    }

    public function updateLoginData(array $userData): bool {
        $account = new AccountModel;
        
        return $account->updateLoginDate($userData['email']);
    }

    public function registerPassword(array $userData) {
        $account = new AccountModel;
        
        return $account->updatePassword(
            $_SESSION['email'],
            password_hash($userData['password'], PASSWORD_DEFAULT)
        );
    }
    
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
}