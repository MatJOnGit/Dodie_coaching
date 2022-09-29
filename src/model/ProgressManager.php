<?php

namespace App\Models;

require_once('./../src/model/Manager.php');

class ProgressManager extends Manager {
    public function addWeightReport(string $memberEmail, float $userWeight, string $reportDate) {
        $db = $this->dbConnect();
        $addWeightReportQuery = 'INSERT INTO users_dynamic_data (report_date, user_id, current_weight) VALUES (?, (SELECT id FROM accounts WHERE email = ?), ?)';
        $addWeightReportStatement = $db->prepare($addWeightReportQuery);
        $addWeightReportStatement->execute([$reportDate, $memberEmail, $userWeight]);
        
        return $addWeightReportStatement->fetchAll();
    }
    
    public function getProgressHistory(string $userEmail) {
        $db = $this->dbConnect();
        $getProgressHistoryQuery = 
        "SELECT udd.report_date, udd.current_weight 
        FROM users_dynamic_data udd INNER JOIN accounts a ON udd.user_id = a.id WHERE a.email = ? ORDER BY report_date DESC LIMIT 10";
        $getProgressHistoryStatement = $db->prepare($getProgressHistoryQuery);
        $getProgressHistoryStatement->execute([$userEmail]);

        return $getProgressHistoryStatement->fetchAll();
    }

    public function deleteReport(string $reportDate, string $memberEmail) {
        $db = $this->dbConnect();
        $deleteReportQuery = "DELETE FROM users_dynamic_data WHERE report_date = ? AND user_id = (SELECT id FROM accounts WHERE email = ?)";
        $deleteReportStatement = $db->prepare($deleteReportQuery);

        return $deleteReportStatement->execute([$reportDate, $memberEmail]);
    }
}