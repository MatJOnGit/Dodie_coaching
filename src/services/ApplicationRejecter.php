<?php

namespace Dodie_Coaching\Services;

class ApplicationRejecter extends Mailer {
    private $_subject = 'Votre demande de suivi nutritionnel';

    private function _getDefaultMessage($applicantData): string { 
        return 
            "<h1 style='font-size: 1.2em;'>Bonjour " . $applicantData['first_name'] . ",</h1><p>Ceci est un mail par défaut pour vous notifier du refus de votre candidature</p>" . $this->signature;
    }

    private function _getCustomMessage($applicantData): string {
        return "<h1 style='font-size: 1.2em;'>Bonjour " . $applicantData['first_name'] . ",</h1>" . htmlspecialchars($_POST['rejection-message']) . $this->signature;
    }

    public function sendNotification(string $messageType, array $applicantData) {
        return 
            $messageType === 'default'
            ? (mail($applicantData['email'], $this->_subject, $this->_getDefaultMessage($applicantData), $this->headers))
            : (mail($applicantData['email'], $this->_subject, $this->_getCustomMessage($applicantData), $this->headers))
        ;
    }
}