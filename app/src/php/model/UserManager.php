<?php

require_once('app/src/php/model/Manager.php');

class UserManager extends Manager {

    public function registerUser($userFirstName, $userLastName, $userMail, $userPassword) {
        $db = $this->dbConnect();
        $userRegistrationQuery = 'INSERT INTO users (first_name, last_name, email, password) VALUES (:firstName, :lastName, :mail, :password)';
        $userRegistrationStatement = $db->prepare($userRegistrationQuery);
        $userRegistrationSuccess = $userRegistrationStatement->execute([
            'firstName' => $userFirstName,
            'lastName' => $userLastName,
            'mail' => $userMail,
            'password' => $userPassword
        ]);
        
        return $userRegistrationSuccess;
     }

    public function verifyUserMail($userMail) {
        $db = $this->dbConnect();
        $userPasswordGetter = 'SELECT password FROM users WHERE email = ?';
        $userPasswordStatement = $db->prepare($userPasswordGetter);
        $userPasswordStatement->execute([$userMail]);
        $userPassword = $userPasswordStatement->fetch();

        return $userPassword;
    }

    public function updateLastLoginTime($userMail) {
        $db = $this->dbConnect();
    }
}