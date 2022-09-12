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
    
    public function getFormConfirmationPasswordData() {
        return htmlspecialchars($_POST['user-confirmation-password']);
    }

    public function getFormEmailData() {
        return htmlspecialchars($_POST['user-email']);
    }

    public function getFormFirstNameData() {
        return htmlspecialchars($_POST['user-first-name']);
    }

    public function getFormLastNameData() {
        return htmlspecialchars($_POST['user-last-name']);
    }

    public function getFormPasswordData() {
        return htmlspecialchars($_POST['user-password']);
    }
    
    private function getEmailRegex() {
        return $this->emailRegex;
    }

    private function getPasswordRegex() {
        return $this->passwordRegex;
    }

    private function getUsernameRegex() {
        return $this->usernameRegex;
    }

    public function registerNewAccount($userFirstName, $userLastName, $userEmail, $userPassword) {
        $accountManager = new AccountManager;

        return $accountManager->registerAccount($userFirstName,$userLastName, $userEmail, $userPassword);
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

    public function setSessionData() {
        $_SESSION['user-email'] = htmlspecialchars($_POST['user-email']);
        $_SESSION['user-password'] = htmlspecialchars($_POST['user-password']);
    }

    public function updateLoginDate($email) {
        $accountManager = new AccountManager;
        return $accountManager->updateMemberLastLogin($email);
    }

    public function verifyAccountValidity($email, $password) { //
        $accountManager = new AccountManager;
        $isPasswordMatching = $password === $accountManager->getAccountPassword($email)[0];
        $isPasswordEmpty = empty($password);

        return ($isPasswordMatching && !$isPasswordEmpty);
    }

    public function verifyLoginFormDataValidity() {
        $userEmail = htmlspecialchars($_POST['user-email']);
        $userPassword = htmlspecialchars($_POST['user-password']);

        return (
            (preg_match($this->getEmailRegex(), $userEmail)) && 
            (preg_match($this->getPasswordRegex(), $userPassword))
        );
    }

    public function verifyMemberStaticDataCompletion() {
        $accountManager = new AccountManager;
        $userStaticData = $accountManager->getMemberStaticData($_SESSION['user-email']);

        return (is_array($userStaticData) && !in_array(NULL, $userStaticData));
    }

    public function verifyRegisteringFormValidity($userFirstName, $userLastName, $userEmail, $userPassword, $userConfirmationPassword) {
        return (
            (preg_match($this->getUsernameRegex(), $userFirstName)) &&
            (preg_match($this->getUsernameRegex(), $userLastName)) &&
            (preg_match($this->getEmailRegex(), $userEmail)) &&
            (preg_match($this->getPasswordRegex(), $userPassword)) &&
            (preg_match($this->getPasswordRegex(), $userConfirmationPassword)) &&
            ($userPassword === $userConfirmationPassword)
        );
    }

    public function verifySessionDataValidity() {
        return (isset($_SESSION['user-email']) && isset($_SESSION['user-password']));
    }
}