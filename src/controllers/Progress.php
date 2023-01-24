<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Progress as ProgressModel,  DateTime;

class Progress extends UserPanel {
    private $_dateTypes = ['current-weight', 'old-weight'];
    
    private $_minWeight = 0;
    
    private $_progressScripts = [
        'classes/UserPanels.model',
        'classes/Progress.model',
        'progressApp'
    ];
    
    private $_subMenuPage = 'progress';
    
    public function areBaseFormDataSet(): bool {
        return (isset($_POST['weight']) && (isset($_POST['date-type'])));
    }
    
    public function areBaseFormDataValid(array $baseFormData): bool {
        $weight = $baseFormData['userWeight'];
        $dateType = $baseFormData['dateType'];
        
        $isWeightDataValid = is_numeric($weight) && ($weight > $this->_getMinWeight());
        $isWeightDateTypeValid = in_array($dateType, $this->_getDateTypes());
        
        return ($isWeightDataValid && $isWeightDateTypeValid);
    }
    
    public function areExtendedFormDataSet(): bool {
        return (isset($_POST['report-day']) && (isset($_POST['report-time'])));
    }
    
    public function areExtendedFormDataValid(array $extendedFormData): bool {
        $date = $extendedFormData['day'] . ' ' . $extendedFormData['time'];
        
        return $this->_isDateValid($date, 'Y-m-d H:i');
    }
    
    public function eraseProgressItem(array $progressHistory, string $reportId) {
        $progress = new ProgressModel;
        $reportDate = $progressHistory[$reportId - 1]['date'];
        
        return $progress->deleteReport($reportDate, $_SESSION['email']);
    }
    
    public function getBaseFormData(): array {
        return [
            'userWeight' => floatval(htmlspecialchars($_POST['weight'])),
            'dateType' => htmlspecialchars($_POST['date-type'])
        ];
    }
    
    public function getExtendedFormData(array $baseFormData): array {
        $baseFormData += [
            'day' => htmlspecialchars($_POST['report-day']),
            'time' => htmlspecialchars($_POST['report-time'])
        ];
        
        return $baseFormData;
    }
    
    public function getFormatedBaseFormData(array $reportBaseFormData): array {
        $this->_setTimeZone();
        
        return [
            'formatedUserWeight' => floatval(number_format($reportBaseFormData['userWeight'], 2)),
            'formatedDate' => date('Y-m-d H:i:s')
        ];
    }
    
    public function getFormatedExtendedFormData(array $reportExtendedFormData): array {
        $this->_setTimeZone();
        
        return [
            'formatedUserWeight' => $reportExtendedFormData['userWeight'],
            'formatedDate' =>  $reportExtendedFormData['day'] . ' ' . $reportExtendedFormData['time']
        ];
    }
    
    public function getHistory(): array {
        $progress = new ProgressModel;
        
        return $progress->selectReports($_SESSION['email']);
    }
    
    public function isCurrentWeightReport(array $baseFormData): bool {
        return ($baseFormData['dateType'] === 'current-weight');
    }
    
    public function isReportIdExisting(array $progressHistory, string $reportId): bool {
        return array_key_exists($reportId-1, $progressHistory);
    }
    
    public function isReportIdValid(string $reportId): bool {
        return (is_numeric($reportId) && $reportId>=1);
    }
    
    public function logProgress(array $formatedFormData) {
        $progress = new ProgressModel;
        $userWeight = $formatedFormData['formatedUserWeight'];
        $reportDate = $formatedFormData['formatedDate'];
        
        return $progress->insertReport($_SESSION['email'], $userWeight, $reportDate);
    }
    
    public function renderProgressPage(object $twig): void {
        echo $twig->render('user_panels/progress.html.twig', [
            'stylePaths' => $this->_getUserPanelsStyles(),
            'frenchTitle' => 'progression',
            'appSection' => 'userPanels',
            'prevPanel' => ['dashboard', 'Tableau de bord'],
            'subPanel' => $this->_getUserPanelsSubpanels($this->_subMenuPage),
            'progressHistory' => $this->getHistory(),
            'pageScripts' => $this->_getProgressScripts()
        ]);
    }
    
    private function _getDateTypes(): array {
        return $this->_dateTypes;
    }
    
    private function _getMinWeight(): int {
        return $this->_minWeight;
    }
    
    private function _getProgressScripts(): array {
        return $this->_progressScripts;
    }
    
    private function _isDateValid(string $date, string $format): bool {
        $dateFormat = DateTime::createFromFormat($format, $date);
        
        return ($dateFormat && $dateFormat->format($format) == $date);
    }
}