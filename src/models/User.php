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

    public function selectAccountPassword (string $email) {
        $db = $this->dbConnect();
        $selectAccountPasswordQuery = 'SELECT password FROM accounts WHERE email = ?';
        $selectAccountPasswordStatement = $db->prepare($selectAccountPasswordQuery);
        $selectAccountPasswordStatement->execute([$email]);

        return $selectAccountPasswordStatement->fetch();
    }

    public function selectRemainingAttempts(string $email) {
        $db = $this->dbConnect();
        $selectRemainingAttemptsQuery = 'SELECT remaining_atpt FROM reset_tokens rt INNER JOIN accounts a ON rt.user_id = a.id WHERE a.email = ?';
        $selectRemainingAttemptsStatement = $db->prepare($selectRemainingAttemptsQuery);
        $selectRemainingAttemptsStatement->execute([$email]);

        return $selectRemainingAttemptsStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function selectRole(string $email) {
        $db = $this->dbConnect();
        $selectRoleQuery = 'SELECT status FROM accounts WHERE email = ?';
        $selectRoleStatement = $db->prepare($selectRoleQuery);
        $selectRoleStatement->execute([$email]);

        return $selectRoleStatement->fetch(PDO::FETCH_ASSOC);
    } 

    public function selectTokenDate(string $email) {
        $db = $this->dbConnect();
        $selectTokenDateQuery = 'SELECT generation_date FROM reset_tokens rt INNER JOIN accounts a ON rt.user_id = a.id WHERE a.email = ?';
        $selectTokenDateStatement = $db->prepare($selectTokenDateQuery);
        $selectTokenDateStatement->execute([$email]);

        return $selectTokenDateStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function selectToken(string $email) {
        $db = $this->dbConnect();
        $selectTokenQuery = 'SELECT token FROM reset_tokens rt INNER JOIN accounts a ON rt.user_id = a.id WHERE a.email = ?';
        $selectTokenStatement = $db->prepare($selectTokenQuery);
        $selectTokenStatement->execute([$email]);

        return $selectTokenStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function insertAccount(string $email, string $hashedPassword) {
        $db = $this->dbConnect();
        $insertAccountQuery = 'INSERT INTO accounts (email, password) VALUES (:email, :password)';
        $insertAccountStatement = $db->prepare($insertAccountQuery);

        return $insertAccountStatement->execute([
            'email' => $email,
            'password' => $hashedPassword
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

    public function updateLoginDate(string $email) {
        $db = $this->dbConnect();
        $updateLoginDateQuery = 'UPDATE accounts SET last_login = NOW() WHERE email = ?';
        $updateLoginDateStatement = $db->prepare($updateLoginDateQuery);

        return $updateLoginDateStatement->execute([$email]);
    }

    public function updateRemainingAttempts(string $email) {
        $db = $this->dbConnect();
        $updateRemainingAtptQuery = 'UPDATE reset_tokens rt INNER JOIN accounts a ON rt.user_id = a.id SET rt.remaining_atpt = rt.remaining_atpt - 1 WHERE a.email = ?';
        $updateRemainingAtptStatement = $db->prepare($updateRemainingAtptQuery);

        return $updateRemainingAtptStatement->execute([$email]);
    }

    public function updatePassword($email, $hashedPassword) {
        $db = $this->dbConnect();
        $updatePasswordQuery = 'UPDATE accounts SET password = ? WHERE email = ?';
        $updatePasswordStatement = $db->prepare($updatePasswordQuery);

        return $updatePasswordStatement->execute([$hashedPassword, $email]);
    }
}