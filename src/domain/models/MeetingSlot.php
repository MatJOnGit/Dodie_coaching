<?php

namespace App\Domain\Models;

use App\Mixins;
use PDO;

final class MeetingSlot {
    use Mixins\Database;
    
    public function dbConnect() {
        return $this->connect();
    }
    
    public function deleteMeeting(int $meetingId) {
        $db = $this->dbConnect();
        $deleteMeetingQuery = 'DELETE FROM meeting_slots WHERE slot_id = ?';
        $deleteMeetingStatement = $db->prepare($deleteMeetingQuery);
        
        return $deleteMeetingStatement->execute([$meetingId]);
    }
    
    public function insertMeeting(string $meetingDate) {
        $db = $this->dbConnect();
        $insertMeetingQuery = 'INSERT INTO meeting_slots (slot_date) VALUES (?)';
        $insertMeetingStatement = $db->prepare($insertMeetingQuery);
        
        return $insertMeetingStatement->execute([$meetingDate]);
    }
    
    public function selectAttendedMeetings(int $subscriberId) {
        $db = $this->dbConnect();
        $selectAttendedMeetingsQuery =
            "SELECT
                ms.slot_date,
                ms.user_id,
                ms.slot_id
            FROM meeting_slots ms
            INNER JOIN subscribers_data sub ON sub.user_id = ms.user_id
            WHERE ms.slot_status = 'attended'
            AND sub.user_id = ?
            AND ms.slot_date < NOW()
            ORDER BY ms.slot_date DESC";
        $selectAttendedMeetingsStatement = $db->prepare($selectAttendedMeetingsQuery);
        $selectAttendedMeetingsStatement->execute([$subscriberId]);
        
        return $selectAttendedMeetingsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectAttendeeData(string $meetingId) {
        $db = $this->dbConnect();
        $selectAttendeeDataQuery =
            "SELECT
                CAST(ms.user_id AS UNSIGNED) as 'user_id',
                DATE_FORMAT(ms.slot_date, '%d/%m/%Y') AS 'day',
                DATE_FORMAT(ms.slot_date, '%H\h%i') AS 'time',
                acc.first_name,
                acc.email
            FROM meeting_slots ms
            LEFT JOIN accounts acc ON ms.user_id = acc.id
            WHERE ms.slot_id = ?";
        $selectAttendeeDataStatement = $db->prepare($selectAttendeeDataQuery);
        $selectAttendeeDataStatement->execute([$meetingId]);
        
        return $selectAttendeeDataStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
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
    
    public function selectNextBookedMeetings() {
        $db = $this->dbConnect();
        $selectNextBookedMeetingsQuery =
            "SELECT
                DATE_FORMAT(slot_date, '%d/%m/%Y') AS 'day',
                DATE_FORMAT(ms.slot_date, '%H\h%i') AS 'starting_time',
                CONCAT(acc.first_name, ' ', UPPER(acc.last_name)) as 'name'
            FROM meeting_slots ms
            INNER JOIN accounts acc
            ON ms.user_id = acc.id
            WHERE ms.slot_date > CURRENT_TIMESTAMP
            AND ms.slot_status = 'booked'
            AND ms.user_id > 0";
        $selectNextBookedMeetingsStatement = $db->prepare($selectNextBookedMeetingsQuery);
        $selectNextBookedMeetingsStatement->execute();
        
        return $selectNextBookedMeetingsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectNextMeetings() {
        $db = $this->dbConnect();
        $selectNextMeetingsQuery =
            "SELECT
                DATE_FORMAT(ms.slot_date, '%d/%m/%Y') AS 'day',
                DATE_FORMAT(ms.slot_date, '%H\h%i') AS 'starting_time',
                CONCAT(acc.first_name, ' ', UPPER(acc.last_name)) as 'name',
                slot_id
            FROM meeting_slots ms
            LEFT JOIN accounts acc
            ON ms.user_id = acc.id
            WHERE ms.slot_date > CURRENT_TIMESTAMP
            ORDER by day, starting_time";
        $selectNextMeetingsStatement = $db->prepare($selectNextMeetingsQuery);
        $selectNextMeetingsStatement->execute();
        
        return $selectNextMeetingsStatement->fetchAll(PDO::FETCH_ASSOC);
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
    
    public function updateMeetingToBooked(string $email, string $meetingDate): bool {
        $db = $this->dbConnect();
        $updateMeetingToBookedQuery =
            "UPDATE meeting_slots
            SET
                user_id = (SELECT id FROM accounts WHERE email = ?),
                slot_status = 'booked'
            WHERE slot_date = ?";
        $updateMeetingToBookedStatement = $db->prepare($updateMeetingToBookedQuery);
        
        return $updateMeetingToBookedStatement->execute([$email, $meetingDate]);
    }
    
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