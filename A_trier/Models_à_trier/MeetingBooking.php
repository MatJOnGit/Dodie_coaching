<?php

namespace Dodie_Coaching\Models;

use PDO;

class MeetingBooking extends Main {    
    public function updateMeetingToAvailable(string $email): bool {
        $db = $this->dbConnect();
        $updateMeetingToAvailableQuery =
            "UPDATE meeting_slots
            SET
                user_id = 0,
                slot_status = 'available'
            WHERE user_id = (SELECT id FROM accounts WHERE email = ?)";
        $updateMeetingToAvailableStatement = $db->prepare($updateMeetingToAvailableQuery);
        
        return $updateMeetingToAvailableStatement->execute([$email]);
    }

    
}