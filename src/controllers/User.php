<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Account;
use Dodie_Coaching\Models\ResetToken;
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
    
    private $_authPanelsScripts = [
        'login' => [
            'classes/Fader.model',
            'classes/ConnectionHelper.model',
            'classes/LoginHelper.model',
            'loginApp'
        ],
        'registering' => [
            'classes/Fader.model',
            'classes/ConnectionHelper.model',
            'classes/RegisteringHelper.model',
            'registeringApp'
        ],
        'pwdRetrieving' => [
            'classes/Fader.model',
            'classes/ConnectionHelper.model',
            'classes/PwdRetrievingHelper.model',
            'pwdRetrievingHelper'
        ],
        'tokenSigning' => [
            'classes/Fader.model',
            'classes/ConnectionHelper.model',
            'classes/TokenSigningHelper.model',
            'tokenSigningApp'
        ],
        'pwdEditing' => [
            'classes/Fader.model',
            'classes/ConnectionHelper.model',
            'classes/PasswordEditingHelper.model',
            'passwordEditionApp'
        ]
    ];
    
    private $_authPanelsStyles = [
        'pages/connection-panels',
        'components/header',
        'components/form',
        'components/footer'
    ];
    
    private $_emailRegex = '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/';
    private $_passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{10,50}$/';
    private $_tokenRegex = '/^[A-Z0-9]{6}$/';
    
    private $_tokenGeneratorTimeOut = 3600;
    
    public function areFormDataValid(array $userData): bool {
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
        $resetToken = new ResetToken;
        
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
        $account = new Account;
        
        return $account->selectRole($_SESSION['email']);
    }
    
    public function getSessionizedParam(string $param): string {
        return $_SESSION[$param];
    }
    
    public function getTokenDate(string $email) {
        $resetToken = new ResetToken;
        
        return $resetToken->selectTokenDate($email);
    }
    
    public function isAccountExisting(array $userData): bool {
        $account = new Account;
        
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
        $account = new Account;
        
        return $account->selectEmail($email);
    }
    
    /***************************************************************************************
    Compares the current date with the date when token was generated and including a timeout
    ***************************************************************************************/
    public function isLastTokenOld(array $tokenData): bool {
        date_default_timezone_set('Europe/Paris');
        $isLastTokenOld = false;
        
        $tokenGenerationDate = $tokenData['generation_date'];
        $currentDate = date('Y-m-d H:i:s');
        $newTokenGenerationDate = date('Y-m-d H:i:s', strtotime($tokenGenerationDate) +  $this->_getTokenGeneratorTimeOut());
        
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
        $resetToken = new ResetToken;
        
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
    
    public function logoutUser(): void {
        $this->destroySessionData();
        $this->routeTo('presentation');
    }
    
    public function logUser(array $userData): void {
        $_SESSION['email'] = $userData['email'];
        $_SESSION['password'] = $userData['password'];
    }
    
    public function registerAccount(array $userData) {
        $account = new Account;
        
        return $account->insertAccount(
            $userData['email'],
            password_hash($userData['password'], PASSWORD_DEFAULT)
        );
    }
    
    public function registerPassword(array $userData) {
        $account = new Account;
        
        return $account->updatePassword(
            $_SESSION['email'],
            password_hash($userData['password'], PASSWORD_DEFAULT)
        );
    }
    
    public function registerToken(string $token) {
        $resetToken = new ResetToken;
        
        return $resetToken->insertToken(
            password_hash($token, PASSWORD_DEFAULT),
            $_SESSION['email']
        );
    }
    
    public function renderLoginPage(object $twig): void {
        echo $twig->render('connection_panels/login.html.twig', [
            'stylePaths' => $this->_getAuthPanelsStyles(),
            'frenchTitle' => 'connection',
            'appSection' => 'connectionPanels',
            'pageScripts' => $this->_authPanelsScripts('login')
        ]);
    }
    
    public function renderMailNotificationPage(object $twig): void {
        echo $twig->render('connection_panels/mail-notification.html.twig', [
            'stylePaths' => $this->_getAuthPanelsStyles(),
            'frenchTitle' => "Notification d'email",
            'appSection' => 'connectionPanels'
        ]);
    }
    
    public function renderPasswordEditingPage(object $twig): void {
        echo $twig->render('connection_panels/password-edition.html.twig', [
            'stylePaths' => $this->_getAuthPanelsStyles(),
            'frenchTitle' => 'Edition de votre mot de passe',
            'appSection' => 'connectionPanels',
            'pageScripts' => $this->_authPanelsScripts('pwdEditing')
        ]);
    }
    
    public function renderPasswordRetrievingPage(object $twig): void {
        echo $twig->render('connection_panels/password-retrieving.html.twig', [
            'stylePaths' => $this->_getAuthPanelsStyles(),
            'frenchTitle' => 'mot de passe perdu',
            'appSection' => 'connectionPanels',
            'pageScripts' => $this->_authPanelsScripts('pwdRetrieving')
        ]);
    }
    
    public function renderRegisteringPage(object $twig): void {
        echo $twig->render('connection_panels/registering.html.twig', [
            'stylePaths' => $this->_getAuthPanelsStyles(),
            'frenchTitle' => 'création de compte',
            'appSection' => 'connectionPanels',
            'pageScripts' => $this->_authPanelsScripts('registering')
        ]);
    }
    
    public function renderRetrievedPasswordPage(object $twig): void {
        echo $twig->render('connection_panels/retrieved-password.html.twig', [
            'stylePaths' => $this->_getAuthPanelsStyles(),
            'frenchTitle' => "Mot de passe modifié",
            'passSection' => 'connectionPanels'
        ]);
    }
    
    public function renderTokenSigningPage(object $twig): void {
        echo $twig->render('connection_panels/token-signing.html.twig', [
            'stylePaths' => $this->_getAuthPanelsStyles(),
            'frenchTitle' => 'réinitialisation de mot de passe',
            'appSection' => 'connectionPanels',
            'remainingAttempts' => $this->_getTokenSigningRemainingAttempts(),
            'pageScripts' => $this->_authPanelsScripts('tokenSigning')
        ]);
    }
    
    public function sessionize(array $userData, array $formData) {
        foreach($formData as $formDataItem) {
            $_SESSION[$formDataItem] = $userData[$formDataItem];
        }
    }
    
    public function subtractTokenAttempt() {
        $resetToken = new ResetToken;
        
        return $resetToken->updateRemainingAttempts($_SESSION['email']);
    }
    
    public function unsessionizeData(array $sessionData): void {
        foreach($sessionData as $sessionDataItem) {
            unset($_SESSION[$sessionDataItem]);
        }
    }
    
    public function updateLoginData(array $userData): bool {
        $account = new Account;
        
        return $account->updateLoginDate($userData['email']);
    }
    
    private function _getAuthPanelsStyles(): array {
        return $this->_authPanelsStyles;
    }
    
    private function _getEmailRegex(): string {
        return $this->_emailRegex;
    }
    
    private function _authPanelsScripts(string $page): array {
        return $this->_authPanelsScripts[$page];
    }
    
    private function _getPasswordRegex(): string {
        return $this->_passwordRegex;
    }
    
    private function _getTokenGeneratorTimeOut(): int {
        return $this->_tokenGeneratorTimeOut;
    }
    
    private function _getTokenRegex(): string {
        return $this->_tokenRegex;
    }
    
    private function _getTokenSigningRemainingAttempts() {
        $resetToken = new ResetToken;
        
        return $resetToken->selectRemainingAttempts($_SESSION['email'])['remaining_atpt'];
    }
}