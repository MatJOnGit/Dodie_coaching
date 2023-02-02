<?php

namespace App\Entities;

use App\Domain\Models\Progress;

class ProgressReport {
    public function isCurrentWeight(array $baseFormData): bool {
        return ($baseFormData['dateType'] === 'current-weight');
    }

    public function logProgress(array $formatedFormData) {
        $progress = new Progress;

        $userWeight = $formatedFormData['formatedUserWeight'];
        $reportDate = $formatedFormData['formatedDate'];
        
        return $progress->insertReport($_SESSION['email'], $userWeight, $reportDate);
    }
    
    public function isReportIdExisting(array $progressHistory, string $reportId): bool {
        return array_key_exists($reportId-1, $progressHistory);
    }

    public function isReportIdValid(string $reportId): bool {
        return (is_numeric($reportId) && $reportId>=1);
    }

    public function eraseProgressReport(array $progressHistory, string $reportId) {
        $progress = new Progress;
        $reportDate = $progressHistory[$reportId - 1]['date'];
        
        return $progress->deleteReport($reportDate, $_SESSION['email']);
    }
}