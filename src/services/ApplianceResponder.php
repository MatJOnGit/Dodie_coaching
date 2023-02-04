<?php

namespace App\Services;

final class ApplianceResponder extends Mailer {
    private const SUBJECT = 'Votre demande de suivi nutritionnel';
    
    public function sendApprovalNotification(array $applicantData) {
        return mail($applicantData['email'], self::SUBJECT, $this->_getApprovalMessage($applicantData), $this->getHeaders());
    }
    
    public function sendRejectionNotification(string $messageType, array $applicantData) {        
        return
            $messageType === 'default'
            ? (mail($applicantData['email'], self::SUBJECT, $this->_getDefaultRejectionMessage($applicantData), $this->getHeaders()))
            : (mail($applicantData['email'], self::SUBJECT, $this->_getCustomRejectionMessage($applicantData), $this->getHeaders()));
    }
    
    private function _getApprovalMessage($applicantData): string {
        return 
            "<h1 style='font-size: 1.2em;'>Bonjour " . $applicantData['first_name'] . ",</h1>
            
            <p>Félicitation, votre candidature pour être suivi par un coach a été acceptée !</p>
            <p>Vous pouvez dorénavant vous connecter à votre espace pour vous abonner au service.</p>
            
            <p>Par la suite, vous pourrez convenir d'un créneau horaire pour votre premier rendez-vous.</p>
            
            <p>A tout de suite !</p>"
            . $this->getSignature();
    }
    
    private function _getCustomRejectionMessage($applicantData): string {
        return
            "<h1 style='font-size: 1.2em;'>Bonjour " . $applicantData['first_name'] . ",</h1>"
            . htmlspecialchars($_POST['rejection-message'])
            . $this->getSignature();
    }
    
    private function _getDefaultRejectionMessage($applicantData): string { 
        return 
            "<h1 style='font-size: 1.2em;'>Bonjour " . $applicantData['first_name'] . ",</h1><p>Ceci est un mail par défaut pour vous notifier du refus de votre candidature</p>" . $this->getSignature();
    }

    private function getSubject() {
        return self::SUBJECT;
    }
}