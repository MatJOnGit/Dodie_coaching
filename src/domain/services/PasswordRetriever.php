<?php

namespace App\Domain\Services;

class PasswordRetriever extends Mailer {
    private const SUBJECT = 'Récupération de votre mot de passe';
    
    public function sendToken(string $token) {
        return mail($this->_getMailTo(), self::SUBJECT, $this->_getPwdRetrievingMessage($token), parent::HEADERS);
    }
    
    protected function _getMailTo(): string {
        return $_SESSION['email'];
    }
    
    private function _getPwdRetrievingMessage(string $token): string { 
        return 
            "<p>Bonjour.</p>
            <p>Un petit problème pour vous connecter à votre compte Dodie Coaching ?<br>
            Nous allons régler ça en un rien de temps !</p>
            
            <p>Tout d'abord, copier le code ci-dessous :</p>
            <div>
                <p>
                    <b>" . $token . "</b>
                </p>
            </div>
            <p>Coller-le ensuite dans le champs à <a href='http://localhost:8080/Dodie_coaching/public/index.php?page=token-signing'>cette adresse</a>.</p>
            
            <p>Vous pourrez par la suite choisir un nouveau mot de passe.</p>
            
            <p>A tout de suite !</p>"
            . parent::SIGNATURE;
    }
}