<?php

namespace App\Domain\Models;

use App\Mixins;
use PDO;

class Meeting {
    use Mixins\Database;

    public function selectAvailableMeetings(int $appointmentDelay) {
        $db = $this->dbConnect();
        $selectAvailableMeetingsQuery =
            "SELECT slot_date
            FROM meeting_slots
            WHERE slot_date >= (CURRENT_TIMESTAMP + interval ? DAY_HOUR)
            AND slot_status = 'available'
            AND user_id = 0 
            ORDER BY slot_date";
        $selectAvailableMeetingsStatement = $db->prepare($selectAvailableMeetingsQuery);
        $selectAvailableMeetingsStatement->execute([$appointmentDelay]);
        
        return $selectAvailableMeetingsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectScheduledMeeting(string $email) {
        $db = $this->dbConnect();
        $selectScheduledMeetingQuery =
            "SELECT ms.slot_date
            FROM meeting_slots ms
            INNER JOIN accounts acc ON ms.user_id = acc.id
            WHERE acc.email= ?
            AND ms.slot_date > (CURRENT_TIMESTAMP)
            ORDER BY ms.slot_date DESC
            LIMIT 1";
        $selectScheduledMeetingStatement = $db->prepare($selectScheduledMeetingQuery);
        $selectScheduledMeetingStatement->execute([$email]);
        
        return $selectScheduledMeetingStatement->fetchAll();
    }

    public function dbConnect() {
        return $this->connect();
    }
}