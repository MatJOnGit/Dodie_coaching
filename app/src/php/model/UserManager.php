<?php

require_once('app/src/php/model/Manager.php');

class UserManager extends Manager {

    public function registerUser($userFirstName, $userLastName, $userEmail, $userPassword) {
        $db = $this->dbConnect();
        $userRegistrationQuery = 'INSERT INTO users (first_name, last_name, email, password) VALUES (:firstName, :lastName, :email, :password)';
        $userRegistrationStatement = $db->prepare($userRegistrationQuery);
        $userRegistrationSuccess = $userRegistrationStatement->execute([
            'firstName' => $userFirstName,
            'lastName' => $userLastName,
            'email' => $userEmail,
            'password' => $userPassword
        ]);
        
        return $userRegistrationSuccess;
     }

    public function getUserPasswordFromEmail($userEmail) {
        $db = $this->dbConnect();
        $userPasswordGetterQuery = 'SELECT password FROM users WHERE email = ?';
        $userPasswordGetterStatement = $db->prepare($userPasswordGetterQuery);
        $userPasswordGetterStatement->execute([$userEmail]);
        $userPassword = $userPasswordGetterStatement->fetch();

        return $userPassword;
    }

    public function updateUserLastLogin($userEmail) {
        $db = $this->dbConnect();
        $userLastLoginUpdaterQuery = 'UPDATE users SET last_login = NOW() WHERE email = ?';
        $userLastLoginUpdaterStatement = $db->prepare($userLastLoginUpdaterQuery);
        $userLastLoginUpdateSuccess = $userLastLoginUpdaterStatement->execute([$userEmail]);

        return $userLastLoginUpdateSuccess;
    }
}