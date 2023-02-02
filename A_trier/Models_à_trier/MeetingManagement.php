<?php

namespace Dodie_Coaching\Models;

use PDO;

class MeetingManagement extends Main {
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
            FROM subscribers_data sub
            INNER JOIN meeting_slots ms ON sub.user_id = ms.user_id
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
                CAST(ms.user_id AS int),
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
                DATE_FORMAT(slot_date, '%d/%m/%Y') AS 'day',
                DATE_FORMAT(ms.slot_date, '%H\h%i') AS 'starting_time',
                CONCAT(acc.first_name, ' ', UPPER(acc.last_name)) as 'name',
                slot_id FROM meeting_slots ms
            LEFT JOIN accounts acc
            ON ms.user_id = acc.id
            WHERE ms.slot_date > CURRENT_TIMESTAMP";
        $selectNextMeetingsStatement = $db->prepare($selectNextMeetingsQuery);
        $selectNextMeetingsStatement->execute();
        
        return $selectNextMeetingsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
}