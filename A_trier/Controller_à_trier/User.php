<?php

namespace Dodie_Coaching\Controller;

use Dodie_Coaching\Models\ResetToken;
use Dodie_Coaching\Models\StaticData;

abstract class User {
    public const EMAIL_REGEX = '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/';
    public const PASSWORD_REGEX = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{10,50}$/';
    public const TOKEN_REGEX = '/^[A-Z0-9]{6}$/';
    
    public const TOKEN_GENERATOR_TIMEOUT = 3600;
    
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
    
    public function registerToken(string $token) {
        $resetToken = new ResetToken;
        
        return $resetToken->insertToken(
            password_hash($token, PASSWORD_DEFAULT),
            $_SESSION['email']
        );
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
    
    private function _getEmailRegex(): string {
        return self::EMAIL_REGEX;
    }
    
    private function _getPasswordRegex(): string {
        return self::PASSWORD_REGEX;
    }
    
    private function _getTokenGeneratorTimeOut(): int {
        return self::TOKEN_GENERATOR_TIMEOUT;
    }
    
    private function _getTokenRegex(): string {
        return self::TOKEN_REGEX;
    }
}