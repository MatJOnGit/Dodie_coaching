<?php

namespace App\Controllers;

require_once ('./../src/model/UserManager.php');

use \App\Models\UserManager;

class UserController {
    private $_emailRegex = '#^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$#';
    
    private $_passwordRegex = '#^(?=.*[A-Z])(?=.*[0-9])(?=.*[a-z]).{6,}$#';

    private $_usernameRegex = '^[-[:alpha:] \']+$^';

    private $_connectionPagesStyles = [
        'pages/connection-panels',
        'components/header',
        'components/form',
        'components/buttons',
        'components/footer'
    ];

    private $_connectionPanelsURLs = [
        'login' => 'index.php?page=login',
        'registering' => 'index.php?page=registering',
        'dashboard' => 'index.php?page=dashboard'
    ];

    public function areMemberStaticDataCompleted() {
        $userManager = new UserManager;
        $userStaticData = $userManager->getMemberStaticData($_SESSION['user-email']);
        return (is_array($userStaticData) && !in_array(NULL, $userStaticData));
    }

    public function destroySessionData() {
        session_destroy();
    }

    public function getConnectionPanelsURL($panel) {
        return $this->_connectionPanelsURLs[$panel];
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

    public function isAccountValid(string $email, string $password) {
        $userManager = new UserManager;
        $dbUserData = $userManager->getMemberPassword($email);

        if ($dbUserData) {
            $isPasswordMatching = $password === $dbUserData[0];
            $isPasswordEmpty = empty($password);

            $isUserVerified = ($isPasswordMatching && !$isPasswordEmpty);
        }

        else {
            $isUserVerified = false;
        }

        return $isUserVerified;
    }

    public function isLoginFormValid(array $userData) {
        return (
            (preg_match($this->_getEmailRegex(), $userData['email'])) && 
            (preg_match($this->_getPasswordRegex(), $userData['password']))
        );
    }

    public function isRegisteringFormValid(array $userData) {
        return (
            (preg_match($this->_getUsernameRegex(), $userData['firstName'])) &&
            (preg_match($this->_getUsernameRegex(), $userData['lastName'])) &&
            (preg_match($this->_getEmailRegex(), $userData['email'])) &&
            (preg_match($this->_getPasswordRegex(), $userData['password'])) &&
            (preg_match($this->_getPasswordRegex(), $userData['confirmationPassword'])) &&
            ($userData['password'] === $userData['confirmationPassword'])
        );
    }

    public function isUserLogged() {
        return (isset($_SESSION['user-email']) && isset($_SESSION['user-password']));
    }

    public function logUser(array $userData) {
        $_SESSION['user-email'] = $userData['email'];
        $_SESSION['user-password'] = $userData['password'];
    }

    public function logUserLoginDate(string $email) {
        $userManager = new UserManager;
        return $userManager->updateMemberLastLogin($email);
    }

    public function registerAccount(array $userData) {
        $userManager = new UserManager;
        return $userManager->registerUser($userData['firstName'], $userData['lastName'], $userData['email'], $userData['password']);
    }

    public function renderLoginPage(object $twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getConnectionPagesStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'connection']);
        echo $twig->render('connection_panels/login.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    public function renderPasswordRetrievingPage(object $twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getConnectionPagesStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'connection']);
        echo $twig->render('connection_panels/password-retrieving.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    public function renderRegisteringPage(object $twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getConnectionPagesStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage'=> 'connection']);
        echo $twig->render('connection_panels/registering.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    private function _getConnectionPagesStyles() {
        return $this->_connectionPagesStyles;
    }
    
    private function _getEmailRegex() {
        return $this->_emailRegex;
    }

    private function _getPasswordRegex() {
        return $this->_passwordRegex;
    }

    private function _getUsernameRegex() {
        return $this->_usernameRegex;
    }
}