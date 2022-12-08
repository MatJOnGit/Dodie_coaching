<?php

namespace Dodie_Coaching\Services;

class CanceledMeetingAlerter extends Mailer {
    private $_subject = 'Votre rendez-vous de suivi';

    private function _getMeetingCancellationMessage($attendeeData) {
        return
            "<h1 style='font-size: 1.2em'>Bonjour " . $attendeeData['first_name'] . ",</h1>
                
            <p>Malheureusement, notre rendez-vous prévu le " . $attendeeData['day'] . " à " . $attendeeData['time'] . " va devoir exceptionnellement être reprogrammé.</p>
            
            <p>Nous vous invitons à planifier un nouveau rendez-vous, à votre convenance.</p>
            
            <p>Nous vous prions de nous excuser pour la gêne occasionnelle.</p>
            
            <p>A très bientôt !</p>"
            .$this->signature;
    }

    public function sendCancelMeetingNotification(array $attendeeData) {
        return mail($attendeeData['email'], $this->_subject, $this->_getMeetingCancellationMessage($attendeeData), $this->headers);
    }
}