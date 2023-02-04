<?php

namespace App\Services;

final class ProgramUpdateNotifier extends Mailer {
    private const SUBJECT = 'Votre nouveau programme nutritionnel';
    
    public function sendProgramFileNotification(array $subscriberHeaders) {
        return mail($subscriberHeaders['email'], $this->getSubject(), $this->_getProgramFileMessage($subscriberHeaders), $this->getHeaders());
    }
    
    private function _getProgramFileMessage($subscriberHeaders) {
        return
            "<h1 style='font-size: 1.2em'>Bonjour " . $subscriberHeaders['first_name'] . ",</h1>
            
            <p>Votre nouveau programme nutritionnel est prêt !</p>
            
            <p>Nous vous invitons à en prendre connaissance dans votre espace personnel.</p>
            
            <p>A tout de suite !</p>"
            . $this->getSignature();
    }

    private function getSubject() {
        return self::SUBJECT;
    }
}