<?php

namespace Dodie_Coaching\Services;

class ProgramFileAlerter extends Mailer {
    private $_subject = 'Votre nouveau programme nutritionnel';

    private function _getProgramFileMessage($subscriberHeaders) {
        return
            "<h1 style='font-size: 1.2em'>Bonjour " . $subscriberHeaders['first_name'] . ",</h1>
                    
            <p>Votre nouveau programme nutritionnel est prêt !</p>
            
            <p>Nous vous invitons à en prendre connaissance dans votre espace personnel.</p>
            
            <p>A tout de suite !</p>"
            .$this->signature;
    }

    public function sendProgramFileNotification(array $subscriberHeaders) {
        return mail($subscriberHeaders['email'], $this->_subject, $this->_getProgramFileMessage($subscriberHeaders), $this->headers);
    }
}