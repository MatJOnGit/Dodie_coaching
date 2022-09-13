<?php

require_once('./../src/model/Manager.php');

class AccountManager extends Manager {
    public function getAccountPassword($userEmail) {
        $db = $this->dbConnect();
        $userPasswordGetterQuery = 'SELECT password FROM accounts WHERE email = ?';
        $userPasswordGetterStatement = $db->prepare($userPasswordGetterQuery);
        $userPasswordGetterStatement->execute([$userEmail]);
        $userPassword = $userPasswordGetterStatement->fetch();

        return $userPassword;
    }

    public function getMemberStaticData($accountEmail) {
        $db = $this->dbConnect();
        $userStaticDataGetterQuery = 'SELECT usd.* FROM users_static_data usd INNER JOIN accounts a ON usd.user_id = a.id WHERE a.email = ?';
        $userStaticDataGetterStatement = $db->prepare($userStaticDataGetterQuery);
        $userStaticDataGetterStatement->execute([$accountEmail]);
        $userStaticData = $userStaticDataGetterStatement->fetch(PDO::FETCH_ASSOC);

        return $userStaticData;
    }

    public function registerAccount($userFirstName, $userLastName, $userEmail, $userPassword) {
        $db = $this->dbConnect();
        $userRegistrationQuery = 'INSERT INTO accounts (first_name, last_name, email, password) VALUES (:firstName, :lastName, :email, :password)';
        $userRegistrationStatement = $db->prepare($userRegistrationQuery);
        $userRegistrationSuccess = $userRegistrationStatement->execute([
            'firstName' => $userFirstName,
            'lastName' => $userLastName,
            'email' => $userEmail,
            'password' => $userPassword
        ]);
        
        return $userRegistrationSuccess;
    }

    public function updateMemberLastLogin($userEmail) {
        $db = $this->dbConnect();
        $userLastLoginUpdaterQuery = 'UPDATE accounts SET last_login = NOW() WHERE email = ?';
        $userLastLoginUpdaterStatement = $db->prepare($userLastLoginUpdaterQuery);
        $userLastLoginUpdateSuccess = $userLastLoginUpdaterStatement->execute([$userEmail]);

        return $userLastLoginUpdateSuccess;
    }
}