<?php

namespace Dodie_Coaching\Models;

use PDO;

class User extends Main {
    public function deleteToken(string $email) {
        $db = $this->dbConnect();
        $deleteTokenQuery = 'DELETE rt FROM reset_tokens rt INNER JOIN accounts a ON rt.user_id = a.id WHERE a.email = ?';
        $deleteTokenStatement = $db->prepare($deleteTokenQuery);

        return $deleteTokenStatement->execute([$email]);
    }

    public function selectEmail(string $email) {
        $db = $this->dbConnect();
        $selectEmailQuery = 'SELECT email FROM accounts WHERE email = ?';
        $selectEmailStatement = $db->prepare($selectEmailQuery);
        $selectEmailStatement->execute([$email]);

        return $selectEmailStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function selectPassword(string $email) {
        $db = $this->dbConnect();
        $selectUserPasswordQuery = 'SELECT password FROM accounts WHERE email = ?';
        $selectUserPasswordStatement = $db->prepare($selectUserPasswordQuery);
        $selectUserPasswordStatement->execute([$email]);

        return $selectUserPasswordStatement->fetch();
    }

    public function selectRemainingAttempts(string $email) {
        $db = $this->dbConnect();
        $selectRemainingAttemptsQuery = 'SELECT remaining_atpt FROM reset_tokens rt INNER JOIN accounts a ON rt.user_id = a.id WHERE a.email = ?';
        $selectRemainingAttemptsStatement = $db->prepare($selectRemainingAttemptsQuery);
        $selectRemainingAttemptsStatement->execute([$email]);

        return $selectRemainingAttemptsStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function selectStaticData(string $email) {
        $db = $this->dbConnect();
        $selectStaticDataQuery = 'SELECT usd.* FROM users_static_data usd INNER JOIN accounts a ON usd.user_id = a.id WHERE a.email = ?';
        $selectStaticDataStatement = $db->prepare($selectStaticDataQuery);
        $selectStaticDataStatement->execute([$email]);

        return $selectStaticDataStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function selectTokenDate(string $email) {
        $db = $this->dbConnect();
        $selectTokenDataQuery = 'SELECT generation_date FROM reset_tokens rt INNER JOIN accounts a ON rt.user_id = a.id WHERE a.email = ?';
        $selectTokenDataStatement = $db->prepare($selectTokenDataQuery);
        $selectTokenDataStatement->execute([$email]);

        return $selectTokenDataStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function selectLastTokenData(string $email) {
        $db = $this->dbConnect();
        $selectLastTokenDateQuery = 'SELECT generation_date FROM reset_tokens rt INNER JOIN accounts a ON rt.user_id = a.id WHERE a.email = ?';
        $selectLastTokenDateStatement = $db->prepare($selectLastTokenDateQuery);
        $selectLastTokenDateStatement->execute([$email]);

        return $selectLastTokenDateStatement->fetch(PDO::FETCH_ASSOC);
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
        $insertTokenQuery = 'INSERT INTO reset_tokens (token, user_id) VALUES ((?), (SELECT id FROM accounts WHERE email = ?))';
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