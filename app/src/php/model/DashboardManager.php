<?php

require_once('app/src/php/model/Manager.php');

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
        $isWeightReported = $weightReporterStatement->fetchAll();

        return $isWeightReported;
    }

    public function getUserId ($userEmail) {
        $db = $this->dbConnect();
        $userIdGetterQuery = 'SELECT id from accounts WHERE email = ?';
        $userIdGetterStatement = $db->prepare($userIdGetterQuery);
        $userIdGetterStatement->execute([$userEmail]);
        $userId = $userIdGetterStatement->fetch(PDO::FETCH_ASSOC);

        return $userId['id'];
    }

    public function getMemberProgressHistory($userEmail) {
        $db = $this->dbConnect();
        $memberProgressGetterQuery = "SELECT udd.report_date, udd.current_weight FROM users_dynamic_data udd INNER JOIN accounts a ON udd.user_id = a.id WHERE a.email = ? ORDER BY report_date DESC LIMIT 10";
        $memberProgressGetterStatement = $db->prepare($memberProgressGetterQuery);
        $memberProgressGetterStatement->execute([$userEmail]);
        $memberProgress = $memberProgressGetterStatement->fetchAll();
        
        return $memberProgress;
    }

    public function getAvailableMeetingsSlots($appointmentDelay) {
        $db = $this->dbConnect();
        $availableMeetingSlotsGetterQuery = "SELECT slot_date FROM `schedule_slots` WHERE slot_date >= (CURRENT_TIMESTAMP + interval ? DAY_HOUR) AND slot_status = 'available' ORDER BY slot_date";
        $availableMeetingSlotsGetterStatement = $db->prepare($availableMeetingSlotsGetterQuery);
        $availableMeetingSlotsGetterStatement->execute([$appointmentDelay]);
        $availableMeetingSlots = $availableMeetingSlotsGetterStatement->fetchAll();

        return $availableMeetingSlots;
    }
}