<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\User as UserModel;

class User extends Main {
    private $_connectionPagesStyles = [
        'pages/connection-panels',
        'components/header',
        'components/form',
        'components/buttons',
        'components/footer'
    ];

    private $_pageScripts = [
        'login' => [
            'classes/UserPanels.model',
            'classes/ConnectionHelper.model',
            'classes/LoginHelper.model',
            'loginHelper'
        ],
        'registering' => [
            'classes/UserPanels.model',
            'classes/ConnectionHelper.model',
            'classes/RegisteringHelper.model',
            'registeringHelper'
        ],
        'pwdRetrieving' => [
            'classes/UserPanels.model',
            'classes/ConnectionHelper.model',
            'classes/PwdRetrievingHelper.model',
            'pwdRetrievingHelper'
        ]
    ];
    
    private $_emailRegex = '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/';
    
    private $_passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{10,50}$/';

    protected $_routingURLs = [
        'dashboard' => 'index.php?page=dashboard',
        'login' => 'index.php?page=login',
        'presentation' => 'index.php?page=presentation',
        'registering' => 'index.php?page=registering',
        'pwd-retrieving' => 'index.php?page=password-retrieving',
        'mail-notification' => 'index.php?page=mail-notification'
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

    public function generateToken() {
        return substr(str_shuffle(str_repeat("0123456789ABCDEFGHIJKLMNOPKRSTUVWXYZ", 5)), 0, 6);
    }

    public function getEmail(): string {
        return $_POST['user-email'];
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
            'confirmationPassword' => htmlspecialchars($_POST['user-confirmation-password'])
        ];

        return $userData;
    }

    public function isAccountExisting(array $userData): bool {
        $user = new UserModel;

        $userRegisteredPassword = $user->selectUserPassword($userData['email']);

        if ($userRegisteredPassword) {
            $isPasswordMatching = password_verify($userData['password'], $userRegisteredPassword[0]);
            $isPasswordEmpty = empty($userData['password']);

            $isAccountExisting = ($isPasswordMatching && !$isPasswordEmpty);
        }

        else {
            $isAccountExisting = false;
        }

        return $isAccountExisting;
    }

    public function isTokenSigningRequested(string $page): bool {
        return $page === 'token-signing';
    }

    public function isLoginActionRequested(string $page): bool {
        return $page === 'log-account';
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

    public function isMailNotificationPageRequested(string $page): bool {
        return $page === 'mail-notification';
    }

    public function isPasswordProvided() {
        return isset($_POST['user-password']);
    }

    public function isEmailProvided() {
        return isset($_POST['user-email']);
    }

    public function isRegisteringActionRequested(string $action): bool {
        return $action === 'register-account';
    }

    public function isRegisteringFormValid(array $userData): bool {
        return (
            preg_match($this->_getEmailRegex(), $userData['email']) &&
            preg_match($this->_getPasswordRegex(), $userData['password']) &&
            preg_match($this->_getPasswordRegex(), $userData['confirmationPassword']) &&
            $userData['password'] === $userData['confirmationPassword']
        );
    }

    public function isRegisteringPageRequested(string $page): bool {
        return $page === 'registering';
    }

    public function isSendTokenActionRequested(string $action): bool {
        return $action === 'send-token';
    }

    public function logUser(array $userData) {
        $_SESSION['user-email'] = $userData['email'];
        $_SESSION['user-password'] = $userData['password'];
    }

    public function registerAccount(array $userData) {
        $user = new UserModel;

        return $user->insertAccount(
            $userData['email'],
            password_hash($userData['password'], PASSWORD_DEFAULT)
        );
    }

    public function renderLoginPage(object $twig) {
        echo $twig->render('connection_panels/login.html.twig', [
            'stylePaths' => $this->_getConnectionPagesStyles(),
            'frenchTitle' => 'connection',
            'appSection' => 'connectionPanels',
            'pageScripts' => $this->_getPageScripts('login')
        ]);
    }

    public function renderMailNotificationPage(object $twig) {
        echo $twig->render('connection_panels/mail-notification.html.twig', [
            'stylePaths' => $this->_getConnectionPagesStyles(),
            'frenchTitle' => "Notification d'email",
            'appSection' => 'connectionPanels'
        ]);
    }

    public function renderPasswordRetrievingPage(object $twig) {
        echo $twig->render('connection_panels/password-retrieving.html.twig', [
            'stylePaths' => $this->_getConnectionPagesStyles(),
            'frenchTitle' => 'mot de passe perdu',
            'appSection' => 'connectionPanels',
            'pageScripts' => $this->_getPageScripts('pwdRetrieving')
        ]);
    }

    public function renderRegisteringPage(object $twig) {
        echo $twig->render('connection_panels/registering.html.twig', [
            'stylePaths' => $this->_getConnectionPagesStyles(),
            'frenchTitle' => 'création de compte',
            'appSection' => 'connectionPanels',
            'pageScripts' => $this->_getPageScripts('registering')
        ]);
    }

    public function renderTokenSigningPage(object $twig) {
        echo $twig->render('connection_panels/token-signing.html.twig', [
            'stylePaths' => $this->_getConnectionPagesStyles(),
            'frenchTitle' => 'réinitialisation de mot de passe',
            'appSection' => 'connectionPanels'
        ]);
    }

    public function storeToken(string $token, string $email) {
        $user = new UserModel;

        return $user->insertToken(
            password_hash($token, PASSWORD_DEFAULT),
            $email
        );
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

    private function _getPageScripts($page) {
        return $this->_pageScripts[$page];
    }

    private function _getPasswordRegex() {
        return $this->_passwordRegex;
    }
}