<?php

require_once ('app/src/php/model/AccountManager.php');

class AccountController {
    private function getUserEmail() { //
        return $_SESSION['user-email'];
    }

    public function storeMemberIdentity() { //
        $accountManager = new AccountManager;
        $memberIdentity = $accountManager->getMemberIdentity($_SESSION['user-email']);
        $_SESSION['user-first-name'] = $memberIdentity['first_name'];
        $_SESSION['user-last-name'] = $memberIdentity['last_name'];
    }

    public function verifyAccountPasswordValidity() { //
        $accountManager = new AccountManager;

        return ($_SESSION['user-password'] === $accountManager->getUserPassword($this->getUserEmail())[0]);
    }

    public function verifyMemberStaticDataCompletion() {
        $accountManager = new AccountManager;
        $userStaticData = $accountManager->getMemberStaticData($_SESSION['user-email']);

        return !in_array(NULL, $userStaticData);
    }

    public function verifySessionData() {
        $isMemberVerified = ((isset($_SESSION['user-email'])) && (isset($_SESSION['user-password'])));

        return $isMemberVerified;
    }
}