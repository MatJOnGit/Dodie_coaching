<?php

namespace Dodie_Coaching\Services;

class ApplicationResponder extends Mailer {
    private $_subject = 'Votre demande de suivi nutritionnel';

    private function _getDefaultRejectionMessage($applicantData): string { 
        return 
            "<h1 style='font-size: 1.2em;'>Bonjour " . $applicantData['first_name'] . ",</h1><p>Ceci est un mail par défaut pour vous notifier du refus de votre candidature</p>" . $this->signature;
    }

    private function _getCustomRejectionMessage($applicantData): string {
        return
            "<h1 style='font-size: 1.2em;'>Bonjour " . $applicantData['first_name'] . ",</h1>"
            . htmlspecialchars($_POST['rejection-message'])
            . $this->signature;
    }

    private function _getApprovalMessage($applicantData): string {
        return 
            "<h1 style='font-size: 1.2em'>Bonjour " . $applicantData['first_name'] . ",</h1>

            <p>Félicitation, votre candidature pour être suivi par un coach a été acceptée !</p>
            <p>Vous pouvez dorénavant vous connecter à votre espace pour vous abonner au service.</p>

            <p>Par la suite, vous pourrez convenir d'un créneau horaire pour votre premier rendez-vous.</p>

            <p>A tout de suite !</p>"
            .$this->signature;
    }

    public function sendRejectionNotification(string $messageType, array $applicantData) {
        return 
            $messageType === 'default'
            ? (mail($applicantData['email'], $this->_subject, $this->_getDefaultRejectionMessage($applicantData), $this->headers))
            : (mail($applicantData['email'], $this->_subject, $this->_getCustomRejectionMessage($applicantData), $this->headers))
        ;
    }

    public function sendApprovalNotification(array $applicantData) {
        return mail($applicantData['email'], $this->_subject, $this->_getApprovalMessage($applicantData), $this->headers);
    }
}