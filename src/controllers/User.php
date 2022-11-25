<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Accounts;
use Dodie_Coaching\Models\ResetTokens;
use Dodie_Coaching\Models\StaticData;

class User extends Main {
    protected $_routingURLs = [
        'dashboard' => 'index.php?page=dashboard',
        'login' => 'index.php?page=login',
        'presentation' => 'index.php?page=presentation',
        'registering' => 'index.php?page=registering',
        'mail-notification' => 'index.php?page=mail-notification',
        'edit-password' => 'index.php?page=password-editing',
        'retrieved-password' => 'index.php?page=retrieved-password',
        'admin-dashboard' => 'index.php?page=admin-dashboard',
        'send-token' => 'index.php?action=send-token',
        'token-signing' => 'index.php?page=token-signing'
    ];

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
        ],
        'tokenSigning' => [
            'classes/UserPanels.model',
            'classes/ConnectionHelper.model',
            'classes/TokenSigningHelper.model',
            'tokenSigningHelper'
        ],
        'pwdEditing' => [
            'classes/UserPanels.model',
            'classes/ConnectionHelper.model',
            'classes/PasswordEditingHelper.model',
            'passwordEditingHelper'
        ]
    ];
    
    private $_emailRegex = '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/';
    
    private $_passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{10,50}$/';

    private $_tokenRegex = '/^[A-Z0-9]{6}$/';

    private $_tokenGeneratorTimeOut = 3600;

    public function areFormDataValid(array $userData) {
        $areFormDataValid = true;

        foreach($userData as $userDataItemKey) {
            if (($userDataItemKey === 'email' && !preg_match($this->_getEmailRegex(), $userData['email']))
            || ($userDataItemKey === 'password' && !preg_match($this->_getPasswordRegex(), $userData['password']))
            || ($userDataItemKey === 'confirmation-password' && $userData['password'] !== $userData['confirmation-password'])
            || ($userDataItemKey === 'token' && !preg_match($this->_getTokenRegex(), $userData['token']))) {
                $areFormDataValid = false;
            }
        }

        return $areFormDataValid;
    }

    public function createStaticData(array $userData) {
        $staticData = new StaticData;

        return $staticData->insertStaticData($userData['email']);
    }

    public function eraseToken(string $email) {
        $resetToken = new ResetTokens;

        return $resetToken->deleteToken($email);
    }

    public function generateToken(): string {
        return substr(str_shuffle(str_repeat("0123456789ABCDEFGHIJKLMNOPKRSTUVWXYZ", 5)), 0, 6);
    }

    public function getFormData(array $formData): array {
        $userData = [];
        
        foreach($formData as $formDataItem) {
            $userData += [$formDataItem => htmlspecialchars($_POST[$formDataItem])];
        }
        
        return $userData;
    }
    
    public function getRole() {
        $account = new Accounts;

        return $account->selectRole($_SESSION['email']);
    }

    public function getSessionizedParam(string $param) {
        return $_SESSION[$param];
    }

    public function getTokenDate(string $email) {
        $resetToken = new ResetTokens;

        return $resetToken->selectTokenDate($email);
    }

    public function isAccountExisting(array $userData): bool {
        $account = new Accounts;

        $accountPassword = $account->selectPassword($userData['email']);
        $isAccountExisting = false;

        if ($accountPassword) {
            $isAccountExisting = password_verify($userData['password'], $accountPassword[0]);
        }

        return $isAccountExisting;
    }

    public function isDataSessionized(string $data): bool {
        return isset($_SESSION[$data]);
    }

    public function isEmailExisting(string $email) {
        $account = new Accounts;

        return $account->selectEmail($email);
    }

    public function isGetParamSet(string $param): bool {
        return isset($_GET[$param]);
    }

    public function isLastTokenOld(array $token): bool {
        date_default_timezone_set('Europe/Paris');
        $isLastTokenOld = false;

        $tokenGenerationDate = $token['generation_date'];
        $currentDate = date("Y-m-d H:i:s");
        $newTokenGenerationDate = date('Y-m-d H:i:s', strtotime($tokenGenerationDate) + $this->_getTokenGeneratorTimeOut());
        
        if ($currentDate > $newTokenGenerationDate) {
            $isLastTokenOld = true;
        }

        return $isLastTokenOld;
    }

    public function isLogged(): bool {
        return isset($_SESSION['email']) && isset($_SESSION['password']);
    }

    public function isLoginFormValid(array $userData): bool {
        return (
            (preg_match($this->_getEmailRegex(), $userData['email'])) && 
            (preg_match($this->_getPasswordRegex(), $userData['password']))
        );
    }

    public function isTokenMatching(): bool {
        $resetToken = new ResetTokens;

        $correctToken = $resetToken->selectToken($_SESSION['email']);
        $postedToken = htmlspecialchars($_POST['token']);

        return password_verify(strtoupper($postedToken), $correctToken['token']);
    }

    public function isRoleMatching(array $userRole, array $toMatch): bool {
        $isRoleMatching = false;
        
        if ($userRole) {
            $isRoleMatching = in_array($userRole['status'], $toMatch);
        }

        return $isRoleMatching;
    }

    public function logoutUser() {
        $this->destroySessionData();
        $this->routeTo('presentation');
    }

    public function logUser(array $userData) {
        $_SESSION['email'] = $userData['email'];
        $_SESSION['password'] = $userData['password'];
    }
    
    public function registerAccount(array $userData) {
        $account = new Accounts;

        return $account->insertAccount(
            $userData['email'],
            password_hash($userData['password'], PASSWORD_DEFAULT)
        );
    }

    public function registerPassword(array $userData) {
        $account = new Accounts;

        return $account->updatePassword(
            $_SESSION['email'],
            password_hash($userData['password'], PASSWORD_DEFAULT)
        );
    }

    public function registerToken(string $token) {
        $resetToken = new ResetTokens;

        return $resetToken->insertToken(
            password_hash($token, PASSWORD_DEFAULT),
            $_SESSION['email']
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

    public function renderPasswordEditingPage(object $twig) {
        echo $twig->render('connection_panels/password-edition.html.twig', [
            'stylePaths' => $this->_getConnectionPagesStyles(),
            'frenchTitle' => 'Edition de votre mot de passe',
            'appSection' => 'connectionPanels',
            'pageScripts' => $this->_getPageScripts('pwdEditing')
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

    public function renderRetrievedPasswordPage(object $twig) {
        echo $twig->render('connection_panels/retrieved-password.html.twig', [
            'stylePaths' => $this->_getConnectionPagesStyles(),
            'frenchTitle' => "Mot de passe modifié",
            'passSection' => 'connectionPanels'
        ]);
    }

    public function renderTokenSigningPage(object $twig) {
        echo $twig->render('connection_panels/token-signing.html.twig', [
            'stylePaths' => $this->_getConnectionPagesStyles(),
            'frenchTitle' => 'réinitialisation de mot de passe',
            'appSection' => 'connectionPanels',
            'remainingAttempts' => $this->_getTokenSigningRemainingAttempts(),
            'pageScripts' => $this->_getPageScripts('tokenSigning')
        ]);
    }

    public function sessionize(array $userData, array $formData) {
        foreach($formData as $formDataItem) {
            $_SESSION[$formDataItem] = $userData[$formDataItem];
        }
    }

    public function subtractTokenAttempt() {
        $resetToken = new ResetTokens;

        return $resetToken->updateRemainingAttempts($_SESSION['email']);
    }

    public function unsessionizeData(array $sessionData) {
        foreach($sessionData as $sessionDataItem) {
            unset($_SESSION[$sessionDataItem]);
        }
    }

    public function updateLoginData(array $userData): bool {
        $account = new Accounts;

        return $account->updateLoginDate($userData['email']);
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

    private function _getTokenGeneratorTimeOut() {
        return $this->_tokenGeneratorTimeOut;
    }

    private function _getTokenRegex() {
        return $this->_tokenRegex;
    }

    private function _getTokenSigningRemainingAttempts() {
        $resetToken = new ResetTokens;

        return $resetToken->selectRemainingAttempts($_SESSION['email'])['remaining_atpt'];
    }
}