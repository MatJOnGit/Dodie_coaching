<?php

require_once('./../src/model/Manager.php');

class DashboardManager extends Manager {
    public $dashboardMenuItems = array(
        'nutritionProgram' => array(
            'frenchTitle' => 'Programme nutritionnel',
            'iconClass' => 'bowl-food',
            'link' => 'nutrition-program'
        ),
        'progress' => array(
            'frenchTitle' => 'Progression',
            'iconClass' => 'person-running',
            'link' => 'progress'
        ),
        'meetings' => array(
            'frenchTitle' => 'Rendez-vous',
            'iconClass' => 'calendar',
            'link' => 'meetings'
        ),
        'subscription' => array(
            'frenchTitle' => 'Abonnement',
            'iconClass' => 'star',
            'link' => 'subscription'
        )
    );

    public function addNewWeightReport ($userId, $userWeight, $reportDate) {
        $db = $this->dbConnect();
        $weightReporterQuery = 'INSERT INTO users_dynamic_data (report_date, user_id, current_weight) VALUES (?, ?, ?)';
        $weightReporterStatement = $db->prepare($weightReporterQuery);
        $weightReporterStatement->execute([$reportDate, $userId, $userWeight]);
        
        return $weightReporterStatement->fetchAll();
    }

    public function bookMemberMeeting($memberEmail, $meetingDate) {
        $db = $this->dbConnect();
        $meetingBookingQuery = "UPDATE scheduled_slots sl SET sl.user_id = (SELECT id FROM accounts WHERE email = ?), sl.slot_status = 'booked' WHERE sl.slot_date = ?";
        $meetingBookingQueryStatement = $db->prepare($meetingBookingQuery);
        $meetingBookingQueryStatement->execute([$memberEmail, $meetingDate]);

        return $meetingBookingQueryStatement->fetchAll();
    }

    public function deleteReport($reportDate, $memberEmail) {
        $db = $this->dbConnect();
        $reportDeletionQuery = "DELETE FROM users_dynamic_data WHERE report_date = ? AND user_id = (SELECT id FROM accounts WHERE email = ?)";
        $reportDeletionQueryStatement = $db->prepare($reportDeletionQuery);
        $reportDeletionQueryStatement->execute([$reportDate, $memberEmail]);
    }

    public function getAvailableMeetingsSlots($appointmentDelay) {
        $db = $this->dbConnect();
        $availableMeetingSlotsGetterQuery = "SELECT slot_date FROM scheduled_slots WHERE slot_date >= (CURRENT_TIMESTAMP + interval ? DAY_HOUR) AND slot_status = 'available' AND user_id = 0 ORDER BY slot_date";
        $availableMeetingSlotsGetterStatement = $db->prepare($availableMeetingSlotsGetterQuery);
        $availableMeetingSlotsGetterStatement->execute([$appointmentDelay]);

        return $availableMeetingSlotsGetterStatement->fetchAll();
    }

    public function getDashboardMenuItems() {
        return $this->dashboardMenuItems;
    }

    public function getMemberId ($userEmail) {
        $db = $this->dbConnect();
        $userIdGetterQuery = 'SELECT id from accounts WHERE email = ?';
        $userIdGetterStatement = $db->prepare($userIdGetterQuery);
        $userIdGetterStatement->execute([$userEmail]);
        $userId = $userIdGetterStatement->fetch(PDO::FETCH_ASSOC);

        return $userId['id'];
    }

    public function getMemberNextMeetingSlots($userEmail) {
        $db = $this->dbConnect();
        $memberIncomingMeetingsGetterQuery = "SELECT sl.slot_date FROM scheduled_slots sl INNER JOIN accounts a ON sl.user_id = a.id WHERE a.email= ? AND sl.slot_date > (CURRENT_TIMESTAMP) ORDER BY sl.slot_date DESC LIMIT 1";
        $memberIncomingMeetingsGetterStatement = $db->prepare($memberIncomingMeetingsGetterQuery);
        $memberIncomingMeetingsGetterStatement->execute([$userEmail]);

        return  $memberIncomingMeetingsGetterStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMemberProgressHistory($userEmail) {
        $db = $this->dbConnect();
        $memberProgressGetterQuery = 
        "SELECT udd.report_date, udd.current_weight 
        FROM users_dynamic_data udd INNER JOIN accounts a ON udd.user_id = a.id WHERE a.email = ? ORDER BY report_date DESC LIMIT 10";
        $memberProgressGetterStatement = $db->prepare($memberProgressGetterQuery);
        $memberProgressGetterStatement->execute([$userEmail]);

        return $memberProgressGetterStatement->fetchAll();
    }

    public function releaseMemberAppointmentMeetingSlot($email) {
        $db = $this->dbConnect();
        $meetingSlotReleasingQuery = "UPDATE scheduled_slots SET user_id = 0, slot_status = 'available' WHERE user_id = (SELECT id FROM accounts WHERE email = ?)";
        $meetingSlotReleasingQueryStatement = $db->prepare($meetingSlotReleasingQuery);
        $meetingSlotReleasingQueryStatement->execute([$email]);
    }
}