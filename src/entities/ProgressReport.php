<?php

namespace App\Entities;

use App\Domain\Models\WeightReport;

final class ProgressReport {
    public function eraseProgressReport(array $progressHistory, string $reportId) {
        $progress = new WeightReport;
        $reportDate = $progressHistory[$reportId - 1]['date'];
        
        return $progress->deleteReport($reportDate, $_SESSION['email']);
    }
    
    public function getHistory(): array {
        $weightReport = new WeightReport;
        
        return $weightReport->selectReports($_SESSION['email']);
    }
    
    public function isCurrentWeight(array $baseFormData): bool {
        return ($baseFormData['dateType'] === 'current-weight');
    }
    
    public function isReportIdExisting(array $progressHistory, string $reportId): bool {
        return array_key_exists($reportId-1, $progressHistory);
    }
    
    public function isReportIdValid(string $reportId): bool {
        return (is_numeric($reportId) && $reportId>=1);
    }
    
    public function logProgress(array $formatedFormData) {
        $weightReport = new WeightReport;
        
        $userWeight = $formatedFormData['formatedUserWeight'];
        $reportDate = $formatedFormData['formatedDate'];
        
        return $weightReport->insertReport($_SESSION['email'], $userWeight, $reportDate);
    }
}