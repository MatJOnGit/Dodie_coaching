<?php

namespace Dodie_Coaching\Models;

class ProgressManager extends Manager {
    public function addWeightReport(string $memberEmail, float $userWeight, string $reportDate) {
        $db = $this->dbConnect();
        $addWeightReportQuery = 'INSERT INTO users_weight_reports (date, user_id, weight) VALUES (?, (SELECT id FROM accounts WHERE email = ?), ?)';
        $addWeightReportStatement = $db->prepare($addWeightReportQuery);
        $addWeightReportStatement->execute([$reportDate, $memberEmail, $userWeight]);
        
        return $addWeightReportStatement->fetchAll();
    }
    
    public function getProgressHistory(string $userEmail) {
        $db = $this->dbConnect();
        $getProgressHistoryQuery = 
        "SELECT uwr.date, uwr.weight FROM users_weight_reports uwr INNER JOIN accounts a ON uwr.user_id = a.id WHERE a.email = ? ORDER BY date DESC LIMIT 10";
        $getProgressHistoryStatement = $db->prepare($getProgressHistoryQuery);
        $getProgressHistoryStatement->execute([$userEmail]);

        return $getProgressHistoryStatement->fetchAll();
    }

    public function deleteReport(string $reportDate, string $memberEmail) {
        $db = $this->dbConnect();
        $deleteReportQuery = "DELETE FROM users_weight_reports WHERE date = ? AND user_id = (SELECT id FROM accounts WHERE email = ?)";
        $deleteReportStatement = $db->prepare($deleteReportQuery);

        return $deleteReportStatement->execute([$reportDate, $memberEmail]);
    }
}