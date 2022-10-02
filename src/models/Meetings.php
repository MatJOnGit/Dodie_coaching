<?php

namespace Dodie_Coaching\Models;

class Meetings extends Main {
    public function updateAvailableMeeting(string $email, string $meetingDate): bool {
        $db = $this->dbConnect();
        $updateAvailableMeetingQuery = "UPDATE scheduled_slots sl SET sl.user_id = (SELECT id FROM accounts WHERE email = ?), sl.slot_status = 'booked' WHERE sl.slot_date = ?";
        $updateAvailableMeetingStatement = $db->prepare($updateAvailableMeetingQuery);
        
        return $updateAvailableMeetingStatement->execute([$email, $meetingDate]);
    }

    public function updateBookedMeeting(string $email): bool {
        $db = $this->dbConnect();
        $updateBookedMeetingQuery = "UPDATE scheduled_slots SET user_id = 0, slot_status = 'available' WHERE user_id = (SELECT id FROM accounts WHERE email = ?)";
        $updateBookedMeetingStatement = $db->prepare($updateBookedMeetingQuery);

        return $updateBookedMeetingStatement->execute([$email]);
    }
    
    public function selectAvailableMeetings(int $appointmentDelay) {
        $db = $this->dbConnect();
        $selectAvailableMeetingsQuery = "SELECT slot_date FROM scheduled_slots WHERE slot_date >= (CURRENT_TIMESTAMP + interval ? DAY_HOUR) AND slot_status = 'available' AND user_id = 0 ORDER BY slot_date";
        $selectAvailableMeetingsStatement = $db->prepare($selectAvailableMeetingsQuery);
        $selectAvailableMeetingsStatement->execute([$appointmentDelay]);

        return $selectAvailableMeetingsStatement->fetchAll();
    }

    public function selectScheduledMeeting(string $email) {
        $db = $this->dbConnect();
        $selectScheduledMeetingQuery = "SELECT sl.slot_date FROM scheduled_slots sl INNER JOIN accounts a ON sl.user_id = a.id WHERE a.email= ? AND sl.slot_date > (CURRENT_TIMESTAMP) ORDER BY sl.slot_date DESC LIMIT 1";
        $selectScheduledMeetingStatement = $db->prepare($selectScheduledMeetingQuery);
        $selectScheduledMeetingStatement->execute([$email]);

        return  $selectScheduledMeetingStatement->fetchAll();
    }
}