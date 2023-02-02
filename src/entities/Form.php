<?php

namespace App\Entities;

class Form {
    public const EMAIL_REGEX = '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/';
    public const PASSWORD_REGEX = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{10,50}$/';
    public const TOKEN_REGEX = '/^[A-Z0-9]{6}$/';

    public function areDataPosted(array $postedData) {
        $areDataPosted = true;
        
        foreach($postedData as $postedDataItem) {
            if (!isset($_POST[$postedDataItem])) {
                $areDataPosted = false;
            }
        }
        
        return $areDataPosted;
    }

    public function getData(array $formData): array {
        $userData = [];
        
        foreach($formData as $formDataItem) {
            $userData += [$formDataItem => htmlspecialchars($_POST[$formDataItem])];
        }
        
        return $userData;
    }
    
    public function areDataValid(array $userData): bool {
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
    
    private function _getEmailRegex(): string {
        return self::EMAIL_REGEX;
    }
    
    private function _getPasswordRegex(): string {
        return self::PASSWORD_REGEX;
    }
    
    private function _getTokenRegex(): string {
        return self::TOKEN_REGEX;
    }
}