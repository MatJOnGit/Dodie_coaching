<?php

require_once('./../src/model/Manager.php');

class AccountManager extends Manager {
    public function getAccountPassword($userEmail) {
        $db = $this->dbConnect();
        $userPasswordQuery = 'SELECT password FROM accounts WHERE email = ?';
        $userPasswordStatement = $db->prepare($userPasswordQuery);
        $userPasswordStatement->execute([$userEmail]);

        return $userPasswordStatement->fetch();
    }

    public function getMemberStaticData($userEmail) {
        $db = $this->dbConnect();
        $userStaticDataQuery = 'SELECT usd.* FROM users_static_data usd INNER JOIN accounts a ON usd.user_id = a.id WHERE a.email = ?';
        $userStaticDataStatement = $db->prepare($userStaticDataQuery);
        $userStaticDataStatement->execute([$userEmail]);

        return $userStaticDataStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function registerAccount($userFirstName, $userLastName, $userEmail, $userPassword) {
        $db = $this->dbConnect();
        $userRegistrationQuery = 'INSERT INTO accounts (first_name, last_name, email, password) VALUES (:firstName, :lastName, :email, :password)';
        $userRegistrationStatement = $db->prepare($userRegistrationQuery);

        return $userRegistrationStatement->execute([
            'firstName' => $userFirstName,
            'lastName' => $userLastName,
            'email' => $userEmail,
            'password' => $userPassword
        ]);
    }

    public function updateMemberLastLogin($userEmail) {
        $db = $this->dbConnect();
        $userLastLoginUpdaterQuery = 'UPDATE accounts SET last_login = NOW() WHERE email = ?';
        $userLastLoginUpdaterStatement = $db->prepare($userLastLoginUpdaterQuery);

        return $userLastLoginUpdaterStatement->execute([$userEmail]);
    }
}