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
        $userIdQuery = 'SELECT * FROM users WHERE email = ?';
        $userIdStatement = $db->prepare($userIdQuery);
        $userIdStatement->execute(array($userMail));
        $userId = $userIdStatement->fetch();
        
        return $userId;
    }
}