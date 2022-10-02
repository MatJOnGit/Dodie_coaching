<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\User as UserModel;

class User extends Main {
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

    protected $_routingURLs = [
        'dashboard' => 'index.php?page=dashboard',
        'login' => 'index.php?page=login',
        'presentation' => 'index.php?page=presentation',
        'registering' => 'index.php?page=registering'
    ];

    public function areDataCompleted(): bool {
        $user = new UserModel;
        $staticData = $user->selectStaticData($_SESSION['user-email']);

        return (!in_array(NULL, $staticData));
    }

    public function createStaticData(array $userData) {
        $user = new UserModel;

        return $user->insertStaticData($userData['email']);
    }

    public function destroySessionData() {
        session_destroy();
    }

    public function getLoginFormData(): array {
        $userData = [
            'email' => htmlspecialchars($_POST['user-email']),
            'password' => htmlspecialchars($_POST['user-password'])
        ];

        return $userData;
    }

    public function getRequestedAction(): string {
        return htmlspecialchars($_GET['action']);
    }

    public function getRegistrationFormAdditionalData(array $userData): array {
        $userData += [
            'firstName' => htmlspecialchars($_POST['user-first-name']),
            'lastName' => htmlspecialchars($_POST['user-last-name']),
            'confirmationPassword' => htmlspecialchars($_POST['user-confirmation-password'])
        ];

        return $userData;
    }

    public function isAccountExisting(array $userData): bool {
        $user = new UserModel;

        $userRegisteredPassword = $user->selectUserPassword($userData['email']);

        if ($userRegisteredPassword) {
            $isPasswordMatching = $userData['password'] === $userRegisteredPassword[0];
            $isPasswordEmpty = empty($userData['password']);

            $isAccountExisting = ($isPasswordMatching && !$isPasswordEmpty);
        }

        else {
            $isAccountExisting = false;
        }

        return $isAccountExisting;
    }

    public function isLoginActionRequested(string $action): bool {
        return $action === 'log-account';
    }

    public function isLogoutActionRequested(string $action): bool {
        return $action === 'logout';
    }

    public function isLogged(): bool {
        return isset($_SESSION['user-email']) && isset($_SESSION['user-password']);
    }

    public function isLoginFormValid(array $userData): bool {
        return (
            (preg_match($this->_getEmailRegex(), $userData['email'])) && 
            (preg_match($this->_getPasswordRegex(), $userData['password']))
        );
    }

    public function isLoginPageRequested(string $page): bool {
        return $page === 'login';
    }

    public function isRegisteringActionRequested(string $action): bool {
        return $action === 'register-account';
    }

    public function isRegisteringFormValid(array $userData): bool {
        return (
            preg_match($this->_getUsernameRegex(), $userData['firstName']) &&
            preg_match($this->_getUsernameRegex(), $userData['lastName']) &&
            preg_match($this->_getEmailRegex(), $userData['email']) &&
            preg_match($this->_getPasswordRegex(), $userData['password']) &&
            preg_match($this->_getPasswordRegex(), $userData['confirmationPassword']) &&
            $userData['password'] === $userData['confirmationPassword']
        );
    }

    public function isRegisteringPageRequested(string $page): bool {
        return $page === 'registering';
    }

    public function logUser(array $userData) {
        $_SESSION['user-email'] = $userData['email'];
        $_SESSION['user-password'] = $userData['password'];
    }

    public function registerAccount(array $userData) {
        $user = new UserModel;

        return $user->insertAccount(
            $userData['firstName'],
            $userData['lastName'],
            $userData['email'],
            $userData['password']
        );
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

    public function updateLoginData(array $userData): bool {
        $user = new UserModel;

        return $user->updateLoginDate($userData['email']);
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