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
        $weightReportSetterQuery = 'INSERT INTO users_dynamic_data (report_date, user_id, current_weight) VALUES (?, ?, ?)';
        $weightReportSetterStatement = $db->prepare($weightReportSetterQuery);
        $weightReportSetterStatement->execute([$reportDate, $userId, $userWeight]);
        $isWeightReported = $weightReportSetterStatement->fetchAll();

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
        $memberProgress = $memberProgressGetterStatement->fetchAll(PDO::FETCH_ASSOC);
        
        return $memberProgress;
    }
}