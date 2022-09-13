<?php

require_once ('app/src/php/model/AccountManager.php');

class AccountController {
    private $emailRegex = '#^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$#';
    private $passwordRegex = '#^(?=.*[A-Z])(?=.*[0-9])(?=.*[a-z]).{6,}$#';
    private $usernameRegex = '^[-[:alpha:] \']+$^';

    private $connectionPagesStyles = [
        'pages/connection-panels',
        'components/header',
        'components/form',
        'components/buttons',
        'components/footer'
    ];

    private $connectionPanelsURLs = array(
        'login' => 'index.php?page=login',
        'registering' => 'index.php?page=registering',
        'dashboard' => 'index.php?page=dashboard'
    );

    public function destroySessionData() {
        session_destroy();
    }

    private function getConnectionPagesStyles() {
        return $this->connectionPagesStyles;
    }

    public function getConnectionPanelsURL($panel) {
        return $this->connectionPanelsURLs[$panel];
    }
    
    private function getEmailRegex() {
        return $this->emailRegex;
    }

    public function getLoginFormData() {
        $userData = [
            'email' => htmlspecialchars($_POST['user-email']),
            'password' => htmlspecialchars($_POST['user-password'])
        ];

        return $userData;
    }

    public function getRegistrationFormAdditionalData($userData) {
        $userData += [
            'firstName' => htmlspecialchars($_POST['user-first-name']),
            'lastName' => htmlspecialchars($_POST['user-last-name']),
            'confirmationPassword' => htmlspecialchars($_POST['user-confirmation-password'])
        ];

        return $userData;
    }

    private function getPasswordRegex() {
        return $this->passwordRegex;
    }

    private function getUsernameRegex() {
        return $this->usernameRegex;
    }

    public function registerNewAccount($userData) {
        $accountManager = new AccountManager;

        return $accountManager->registerAccount($userData['firstName'], $userData['lastName'], $userData['email'], $userData['password']);
    }

    public function renderLoginPage($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->getConnectionPagesStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'connection']);
        echo $twig->render('connection_panels/login.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    public function renderPasswordRetrievingPage($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->getConnectionPagesStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'connection']);
        echo $twig->render('connection_panels/password-retrieving.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    public function renderRegisteringPage($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->getConnectionPagesStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage'=> 'connection']);
        echo $twig->render('connection_panels/registering.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    public function setSessionData($userData) {
        $_SESSION['user-email'] = $userData['email'];
        $_SESSION['user-password'] = $userData['password'];
    }

    public function updateLoginDate($email) {
        $accountManager = new AccountManager;
        return $accountManager->updateMemberLastLogin($email);
    }

    public function verifyAccountValidity($email, $password) {
        $accountManager = new AccountManager;
        $dbUserData = $accountManager->getAccountPassword($email);

        if ($dbUserData) {
            $isPasswordMatching = $password === $dbUserData[0];
            $isPasswordEmpty = empty($password);

            $isAccountKnown = ($isPasswordMatching && !$isPasswordEmpty);
        }

        else {
            $isAccountKnown = false;
        }

        return $isAccountKnown;
    }

    public function verifyLoginFormDataValidity($userData) {
        return (
            (preg_match($this->getEmailRegex(), $userData['email'])) && 
            (preg_match($this->getPasswordRegex(), $userData['password']))
        );
    }

    public function verifyMemberStaticDataCompletion() {
        $accountManager = new AccountManager;
        $userStaticData = $accountManager->getMemberStaticData($_SESSION['user-email']);

        return (is_array($userStaticData) && !in_array(NULL, $userStaticData));
    }

    public function verifyRegisteringFormValidity($userData) {
        return (
            (preg_match($this->getUsernameRegex(), $userData['firstName'])) &&
            (preg_match($this->getUsernameRegex(), $userData['lastName'])) &&
            (preg_match($this->getEmailRegex(), $userData['email'])) &&
            (preg_match($this->getPasswordRegex(), $userData['password'])) &&
            (preg_match($this->getPasswordRegex(), $userData['confirmationPassword'])) &&
            ($userData['password'] === $userData['confirmationPassword'])
        );
    }

    public function verifySessionDataValidity() {
        return (isset($_SESSION['user-email']) && isset($_SESSION['user-password']));
    }
}