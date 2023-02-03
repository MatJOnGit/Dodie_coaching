<?php

namespace App\Entities;

final class Session {
    public function sessionize(array $userData, array $formData) {
        foreach($formData as $formDataItem) {
            $_SESSION[$formDataItem] = $userData[$formDataItem];
        }
    }
    
    public function isDataSessionized(string $data): bool {
        return isset($_SESSION[$data]);
    }
    
    public function isUserLogged(): bool {
        return isset($_SESSION['email']) && isset($_SESSION['password']);
    }
    
    public function logUser(array $userData): void {
        $_SESSION['email'] = $userData['email'];
        $_SESSION['password'] = $userData['password'];
    }
    
    public function getSessionizedParam(string $param): string {
        return $_SESSION[$param];
    }
    
    public function unsessionizeData(array $sessionData): void {
        foreach($sessionData as $sessionDataItem) {
            unset($_SESSION[$sessionDataItem]);
        }
    }

    public function logoutUser(): void {
        session_destroy();
        header("location:index.php");
    }
}