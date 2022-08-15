<?php

class MainController {
    public $usernameRegex = '^[-[:alpha:] \']+$^';
    public $emailRegex = '#^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$#';
    public $passwordRegex = '#^(?=.*[A-Z])(?=.*[0-9])(?=.*[a-z]).{6,}$#';

    public function getUserEmail() {
        return $_SESSION['user-email'];
    }

    public function verifyUserInDatabase($email) {
        $accountManager = new AccountManager;
        $dbUserPassword = $accountManager->getUserPassword($email);

        return $dbUserPassword;
    }
}