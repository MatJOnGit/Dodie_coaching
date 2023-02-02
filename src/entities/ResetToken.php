<?php

namespace App\Entities;

use App\Domain\Models\ResetToken as ResetTokenModel;

final class ResetToken {
    public const TOKEN_GENERATOR_TIMEOUT = 3600;

    public function getTokenDate(string $email) {
        $resetToken = new ResetTokenModel;
        
        return $resetToken->selectTokenDate($email);
    }
    
    public function generateToken(): string {
        return substr(str_shuffle(str_repeat("0123456789ABCDEFGHIJKLMNOPKRSTUVWXYZ", 5)), 0, 6);
    }
    
    public function registerToken(string $token) {
        $resetToken = new ResetTokenModel;
        
        return $resetToken->insertToken(
            password_hash($token, PASSWORD_DEFAULT),
            $_SESSION['email']
        );
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
    
    public function eraseToken(string $email) {
        $resetToken = new ResetTokenModel;
        
        return $resetToken->deleteToken($email);
    }
    
    private function _getTokenGeneratorTimeOut(): int {
        return self::TOKEN_GENERATOR_TIMEOUT;
    }
    
    public function isTokenMatching(): bool {
        $resetToken = new ResetTokenModel;
        
        $correctToken = $resetToken->selectToken($_SESSION['email']);
        $postedToken = htmlspecialchars($_POST['token']);
        
        return password_verify(strtoupper($postedToken), $correctToken['token']);
    }
    
    public function subtractTokenAttempt() {
        $resetToken = new ResetTokenModel;
        
        return $resetToken->updateRemainingAttempts($_SESSION['email']);
    }
}