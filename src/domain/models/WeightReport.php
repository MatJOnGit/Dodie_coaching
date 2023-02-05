<?php

namespace App\Domain\Models;

use App\Mixins;

final class WeightReport {
    use Mixins\Database;
    
    public function dbConnect() {
        return $this->connect();
    }
    
    public function deleteReport(string $reportDate, string $email) {
        $db = $this->dbConnect();
        $deleteReportQuery = "DELETE FROM users_weight_reports WHERE date = ? AND user_id = (SELECT id FROM accounts WHERE email = ?)";
        $deleteReportStatement = $db->prepare($deleteReportQuery);
        
        return $deleteReportStatement->execute([$reportDate, $email]);
    }
    
    public function insertReport(string $email, float $userWeight, string $reportDate) {
        $db = $this->dbConnect();
        $insertReportQuery = "INSERT INTO users_weight_reports (date, user_id, weight) VALUES (?, (SELECT id FROM accounts WHERE email = ?), ?)";
        $insertReportStatement = $db->prepare($insertReportQuery);
        
        return $insertReportStatement->execute([$reportDate, $email, $userWeight]);
    }
    
    public function selectReports(string $userEmail) {
        $db = $this->dbConnect();
        $selectReportsQuery = 
        "SELECT uwr.date, uwr.weight FROM users_weight_reports uwr INNER JOIN accounts acc ON uwr.user_id = acc.id WHERE acc.email = ? ORDER BY date DESC LIMIT 10";
        $selectReportsStatement = $db->prepare($selectReportsQuery);
        $selectReportsStatement->execute([$userEmail]);
        
        return $selectReportsStatement->fetchAll();
    }
}