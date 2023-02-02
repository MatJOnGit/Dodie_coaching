<?php

namespace Dodie_Coaching\Services;

class ApplianceResponder extends Mailer {
    private string $_SUBJECT = 'Votre demande de suivi nutritionnel';
    
    public function sendApprovalNotification(array $applicantData) {
        return mail($applicantData['email'], $this->_SUBJECT, $this->_getApprovalMessage($applicantData), $this->HEADERS);
    }
    
    public function sendRejectionNotification(string $messageType, array $applicantData) {
        return 
            $messageType === 'default'
            ? (mail($applicantData['email'], $this->_SUBJECT, $this->_getDefaultRejectionMessage($applicantData), $this->HEADERS))
            : (mail($applicantData['email'], $this->_SUBJECT, $this->_getCustomRejectionMessage($applicantData), $this->HEADERS));
    }
    
    private function _getApprovalMessage($applicantData): string {
        return 
            "<h1 style='font-size: 1.2em'>Bonjour " . $applicantData['first_name'] . ",</h1>
            
            <p>Félicitation, votre candidature pour être suivi par un coach a été acceptée !</p>
            <p>Vous pouvez dorénavant vous connecter à votre espace pour vous abonner au service.</p>
            
            <p>Par la suite, vous pourrez convenir d'un créneau horaire pour votre premier rendez-vous.</p>
            
            <p>A tout de suite !</p>"
            .$this->SIGNATURE;
    }
    
    private function _getCustomRejectionMessage($applicantData): string {
        return
            "<h1 style='font-size: 1.2em;'>Bonjour " . $applicantData['first_name'] . ",</h1>"
            . htmlspecialchars($_POST['rejection-message'])
            . $this->SIGNATURE;
    }
    
    private function _getDefaultRejectionMessage($applicantData): string { 
        return 
            "<h1 style='font-size: 1.2em;'>Bonjour " . $applicantData['first_name'] . ",</h1><p>Ceci est un mail par défaut pour vous notifier du refus de votre candidature</p>" . $this->SIGNATURE;
    }
}