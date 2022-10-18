<?php

namespace Dodie_Coaching\Models;

use PDO;

class User extends Main {
    public function selectUserPassword(string $email) {
        $db = $this->dbConnect();
        $selectUserPasswordQuery = 'SELECT password FROM accounts WHERE email = ?';
        $selectUserPasswordStatement = $db->prepare($selectUserPasswordQuery);
        $selectUserPasswordStatement->execute([$email]);

        return $selectUserPasswordStatement->fetch();
    }

    public function selectStaticData(string $email) {
        $db = $this->dbConnect();
        $selectStaticDataQuery = 'SELECT usd.* FROM users_static_data usd INNER JOIN accounts a ON usd.user_id = a.id WHERE a.email = ?';
        $selectStaticDataStatement = $db->prepare($selectStaticDataQuery);
        $selectStaticDataStatement->execute([$email]);

        return $selectStaticDataStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function insertAccount(string $email, string $userPassword) {
        $db = $this->dbConnect();
        $insertAccountQuery = 'INSERT INTO accounts (email, password) VALUES (:email, :password)';
        $insertAccountStatement = $db->prepare($insertAccountQuery);

        return $insertAccountStatement->execute([
            'email' => $email,
            'password' => $userPassword
        ]);
    }

    public function insertToken(string $token, string $email) {
        $db = $this->dbConnect();
        $insertTokenQuery = 'INSERT INTO pwd_reset_atpt (token, user_id) VALUES ((?), (SELECT id FROM accounts WHERE email = ?))';
        $insertTokenStatement = $db->prepare($insertTokenQuery);

        return $insertTokenStatement->execute([$token, $email]);

    }

    public function insertStaticData(string $email) {
        $db = $this->dbConnect();
        $insertStaticDataQuery = 'INSERT INTO users_static_data (user_id) VALUES ((SELECT id FROM accounts WHERE email = ?))';
        $insertStaticDataStatement = $db->prepare($insertStaticDataQuery);

        return $insertStaticDataStatement->execute([$email]);
    }

    public function updateLoginDate(string $userEmail): bool {
        $db = $this->dbConnect();
        $updateLoginDateQuery = 'UPDATE accounts SET last_login = NOW() WHERE email = ?';
        $updateLoginDateStatement = $db->prepare($updateLoginDateQuery);

        return $updateLoginDateStatement->execute([$userEmail]);
    }
}