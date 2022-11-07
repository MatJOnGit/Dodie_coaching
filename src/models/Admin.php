<?php

namespace Dodie_Coaching\Models;

use PDO;

class Admin extends Main {
    public function selectApplicationsCount() {
        $db = $this->dbConnect();
        $selectApplicationsCountQuery = "SELECT COUNT(user_id) as applicationsCount FROM costumer_applications WHERE status = 'pending'";
        $selectApplicationsCountStatement = $db->prepare($selectApplicationsCountQuery);
        $selectApplicationsCountStatement->execute();

        return $selectApplicationsCountStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function selectSubscribersCount() {
        $db = $this->dbConnect();
        $selectSubscribersCountQuery = "SELECT COUNT(id) as subscribersCount FROM accounts WHERE status = 'subscriber'";
        $selectSubscribersCountStatement = $db->prepare($selectSubscribersCountQuery);
        $selectSubscribersCountStatement->execute();

        return $selectSubscribersCountStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function selectIncomingMeetings() {
        $db = $this->dbConnect();
        $selectIncomingMeetingsQuery = "SELECT sl.slot_date, a.first_name, a.last_name FROM scheduled_slots sl INNER JOIN accounts a ON sl.user_id = a.id WHERE sl.slot_date > CURRENT_TIMESTAMP AND sl.slot_status = 'booked' AND sl.user_id > 0";
        $selectIncomingMeetingsStatement = $db->prepare($selectIncomingMeetingsQuery);
        $selectIncomingMeetingsStatement->execute();

        return $selectIncomingMeetingsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
}