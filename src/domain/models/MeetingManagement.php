<?php

namespace App\Domain\Models;

use App\Mixins;
use PDO;

class MeetingManagement {
    use Mixins\Database;
    
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
    
    public function dbConnect() {
        return $this->connect();
    }
}