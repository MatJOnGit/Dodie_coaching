<?php

namespace Dodie_Coaching\Models;

use PDO;

class Meetings extends Main {
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
        
        return $selectAvailableMeetingsStatement->fetchAll();
    }
    
    public function selectIncomingMeetings() {
        $db = $this->dbConnect();
        $selectIncomingMeetingsQuery =
            "SELECT ms.slot_date,
                acc.first_name,
                acc.last_name
            FROM meeting_slots ms
            INNER JOIN accounts acc ON ms.user_id = acc.id
            WHERE ms.slot_date > CURRENT_TIMESTAMP
            AND ms.slot_status = 'booked'
            AND ms.user_id > 0";
        $selectIncomingMeetingsStatement = $db->prepare($selectIncomingMeetingsQuery);
        $selectIncomingMeetingsStatement->execute();
        
        return $selectIncomingMeetingsStatement->fetchAll(PDO::FETCH_ASSOC);
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
        
        return  $selectScheduledMeetingStatement->fetchAll();
    }
    
    public function selectAttendedMeetings(int $subscriberId) {
        $db = $this->dbConnect();
        $selectAttendedMeetingsQuery =
            "SELECT
                ms.slot_date,
                ms.user_id,
                ms.slot_id
            FROM subscribers sub
            INNER JOIN meeting_slots ms ON sub.user_id = ms.user_id
            WHERE ms.slot_status = 'attended'
            AND sub.user_id = ?
            ORDER BY ms.slot_date DESC";
        $selectAttendedMeetingsStatement = $db->prepare($selectAttendedMeetingsQuery);
        $selectAttendedMeetingsStatement->execute([$subscriberId]);
        
        return $selectAttendedMeetingsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateToBooked(string $email, string $meetingDate): bool {
        $db = $this->dbConnect();
        $updateToBookedQuery = "UPDATE meeting_slots SET user_id = (SELECT id FROM accounts WHERE email = ?), slot_status = 'booked' WHERE slot_date = ?";
        $updateToBookedStatement = $db->prepare($updateToBookedQuery);
        
        return $updateToBookedStatement->execute([$email, $meetingDate]);
    }
    
    public function updateToAvailableMeeting(string $email): bool {
        $db = $this->dbConnect();
        $updateToAvailableMeetingQuery = "UPDATE meeting_slots SET user_id = 0, slot_status = 'available' WHERE user_id = (SELECT id FROM accounts WHERE email = ?)";
        $updateToAvailableMeetingStatement = $db->prepare($updateToAvailableMeetingQuery);
        
        return $updateToAvailableMeetingStatement->execute([$email]);
    }
}